<?php


namespace App\Controller;


use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/projects", name="api_project_list")
     */
    public function listProjects(SerializerInterface $serializer)
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();
        $serializedProjects = $serializer->serialize($projects, 'json', ['groups' => ['api_short']]);
        return new JsonResponse($serializedProjects, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/projects/{id}", name="api_project_show")
     */
    public function showProject(SerializerInterface $serializer, $id)
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->find($id);
        $serializedProjects = $serializer->serialize($projects, 'json', ['groups' => ['api_full']]);
        return new JsonResponse($serializedProjects, Response::HTTP_OK, [], true);
    }
}