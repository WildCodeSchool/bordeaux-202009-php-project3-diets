<?php

namespace App\Controller;

use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/knowledge", name="knowledge")
 */
class KnowledgeController extends AbstractController
{
    /**
     * @Route("/", name="_index")
     */
    public function index(ResourceRepository $resourceRepository): Response
    {
        $resource = $resourceRepository->findBy([], ['updatedAt'=> 'DESC'], 10 );
        /*$service = $this->getDoctrine()
            ->getRepository(Service::class)
            ->findBy(array(), array('id' => 'desc'));*/

        return $this->render('knowledge/index.html.twig', [
            'resources' => $resource,
        ]);
    }
}
