<?php


namespace App\Controller;


use App\Entity\Project;
use App\Form\ProjectStatusType;
use App\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project_list")
     */
    public function list()
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();

        return $this->render('project/list.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/project/add", name="project_add")
     */
    public function add(Request $request)
    {
        $project = new Project();
        $projectForm = $this->createForm(ProjectType::class, $project);

        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $project->setStartedAt(new \DateTime());
            $project->setStatus(Project::STATUS_NEW);
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/add.html.twig', [
            'projectForm' => $projectForm->createView()
        ]);
    }

    /**
     * @Route("/project/{id}", name="project_manage")
     */
    public function manage(Request $request, $id)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        // return to the projects list of the project is not found
        if ($project === null) {
            return $this->redirectToRoute('project_list');
        }

        $projectStatusForm = $this->createForm(ProjectStatusType::class, $project);

        // process the form to change the status only if the the status is not already "done"
        if ($project->getStatus() !== Project::STATUS_DONE) {
            $projectStatusForm->handleRequest($request);
            if ($projectStatusForm->isSubmitted() && $projectStatusForm->isValid()) {
                // if the status is changed to "done", we fill the endedAt field with the current date
                if ($project->getStatus() === Project::STATUS_DONE) {
                    $project->setEndedAt(new \DateTime());
                }
                $this->getDoctrine()->getManager()->persist($project);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $this->render('project/manage.html.twig', [
            'project' => $project,
            'projectStatusForm' => $projectStatusForm->createView()
        ]);
    }
}