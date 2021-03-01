<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Resource;
use App\Form\ResourcesAllType;
use App\Form\SearchResourceType;
use App\Repository\EventRepository;
use App\Repository\PathologyRepository;
use App\Repository\ResourceFormatRepository;
use App\Repository\ResourceRepository;
use App\Repository\ServiceRepository;
use Container1zMksP6\getDoctrine_DatabaseDropCommandService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/ressource", name="ressource_")
 */
class RessourceController extends AbstractController
{
    private const NBRESOURCE = 12;
    /**
     * @Route("/", name="index")
     */
    public function index(ResourceRepository $resourceRepository,
                          Request $request,
                          ServiceRepository $serviceRepository,
                          EventRepository $eventRepository): Response
    {

        $events = $eventRepository->nextEventByFour();



        $resourcesLastUpdate = $resourceRepository->findBy(
            [
            ],
            [
                'updatedAt' => 'DESC'
            ],
            self::NBRESOURCE
        );

        $servicesLastUpdate = $serviceRepository->findBy(
            [
                'serviceIsValidated' => true
            ],
            [
                'id' => 'DESC'
            ],
            self::NBRESOURCE
        );

        return $this->render('ressource/index.html.twig', [
            'form' => 'form',
            'events' => $events,
            'resources_last_update' => $resourcesLastUpdate,
            'services_last_update' => $servicesLastUpdate,
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
