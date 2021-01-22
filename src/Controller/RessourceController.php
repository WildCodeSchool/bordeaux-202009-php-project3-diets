<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\SearchResourceType;
use App\Repository\PathologyRepository;
use App\Repository\ResourceFormatRepository;
use App\Repository\ResourceRepository;
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
                          Request $request): Response
    {
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            $pathology = $formSearch->getData()['pathology'];
            $format = $formSearch->getData()['format'];
            $resourcesSearch = $resourceRepository->searchByPathologyFormatAndLikeName($search, $pathology, $format);
        } else {
            $resourcesSearch = [];
        }

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findBy(array(), array('dateStart' => 'desc'), 1);



        $resourcesLastUpdate = $resourceRepository->findBy(
            [
            ],
            [
                'updatedAt' => 'DESC'
            ],
            self::NBRESOURCE
        );


        return $this->render('ressource/index.html.twig', [
            'form' => 'form',
            'events' => $event,
            'resourcesLastUpdate' => $resourcesLastUpdate,
            'resourcesSearch' => $resourcesSearch,
            'formSearch' => $formSearch->createView(),
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
