<?php


namespace App\Controller;


use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/project/{projectId}/tasks/add", name="task_add")
     */
    public function add(Request $request, $projectId)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);
        // return to the projects list of the project is not found
        if ($project === null) {
            return $this->redirectToRoute('project_list');
        }
        // return to the project page if its status is "done" (you cannot add new tasks)
        if ($project->getStatus() === Project::STATUS_DONE) {
            return $this->redirectToRoute('project_manage', ['id' => $project->getId()]);
        }

        $task = new Task();
        $taskForm = $this->createForm(TaskType::class, $task);

        $taskForm->handleRequest($request);
        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $task->setCreatedAt(new \DateTime());
            $task->setProject($project);
            $this->getDoctrine()->getManager()->persist($task);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_manage', ['id' => $project->getId()]);
        }

        return $this->render('task/add.html.twig', [
            'project' => $project,
            'taskForm' => $taskForm->createView()
        ]);
    }
}