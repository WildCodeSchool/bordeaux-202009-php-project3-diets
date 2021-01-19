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

/**
 * @Route("/ressource", name="ressource_")
 */
class RessourceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ResourceRepository $resourceRepository,
                          Request $request,
                          ResourceFormatRepository $resourceFormatRepository,
                          PathologyRepository $pathologyRepository): Response
    {
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            $formats = $resourceFormatRepository->findLikeName($search);
            $pathologies = $pathologyRepository->findLikeName($search);
        } else {
            $formats = [];
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
            12
        );
        return $this->render('ressource/index.html.twig', [
            'form' => 'form',
            'events' => $event,
            'resourcesLastUpdate' => $resourcesLastUpdate,
            'formatsSearch' => $formats,
            'pathologiesSearch' => $pathologies,
            'formSearch' => $formSearch->createView(),
        ]);
    }
}
