<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ressource", name="ressource_")
 */
class RessourceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ResourceRepository $resourceRepository): Response
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findBy(array(), array('dateStart' => 'desc'), 1);

        $resourcesLastUpdate = $resourceRepository->findBy(
            [
            ],
            [
                'updatedAt' => 'DESC'
            ],
            12
        );
        return $this->render('ressource/index.html.twig', [
            'form' => 'form',
            'events' => $event,
            'resourcesLastUpdate' => $resourcesLastUpdate,
        ]);
    }
}
