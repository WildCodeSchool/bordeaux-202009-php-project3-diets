<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

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

    /**
     * @Route("/download", name="download", methods={"POST"})
     */
    public function download(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $path = $_POST['path'];
            $fullPath = 'uploads/resources/' . $path;
            $file = new File($fullPath);
            return $this->file($file);
        }
    }
}
