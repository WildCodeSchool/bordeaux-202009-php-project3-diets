<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Resource;
use App\Form\ResourcesAllType;
use App\Form\SearchResourceType;
use App\Repository\PathologyRepository;
use App\Repository\ResourceFormatRepository;
use App\Repository\ResourceRepository;
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
                          Request $request): Response
    {
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() && $formSearch->isValid()) {
            $formSearch->getData()['search'] !== NULL ?
                $search = $formSearch->getData()['search'] : $search = '';
            $formSearch->getData()['pathology'] !== NULL ?
                $pathology = $formSearch->getData()['pathology']->getIdentifier() : $pathology = '';
            $formSearch->getData()['format'] !== NULL ?
                $format = $formSearch->getData()['format']->getIdentifier() : $format = '';
            if (!$search && !$pathology && !$format) {
                $resourcesSearch = ['last'];
            } elseif ( !$search && !$pathology && $format) {
                $resourcesSearch = $resourceRepository
                    ->searchByFormat($format);
            } elseif (!$search && $pathology && !$format) {
                $resourcesSearch = $resourceRepository
                    ->searchByPathology($pathology);
            }  elseif ($search && !$pathology && !$format) {
                $resourcesSearch = $resourceRepository
                    ->searchLikeName($search);
            } elseif ($search && $pathology && !$format) {
                $resourcesSearch = $resourceRepository
                    ->searchByPathologyAndLikeName($pathology, $search);
            } elseif ($search && !$pathology && $format) {
                $resourcesSearch = $resourceRepository
                    ->searchByFormatAndLikeName($format, $search);
            } elseif (!$search && $pathology && $format) {
                $resourcesSearch = $resourceRepository
                    ->searchByPathologyAndFormat($pathology, $format);
            } else {
                $resourcesSearch = $resourceRepository
                    ->searchByPathologyFormatAndLikeName($pathology, $format,$search);
            }
        } else {
            $resourcesSearch = ['last'];
        }

        $formSearchAll = $this->createForm(ResourcesAllType::class);
        $formSearchAll->handleRequest($request);

        if ($formSearchAll->isSubmitted() && $formSearchAll->isValid()) {
                $resourcesSearch = $resourceRepository
                    ->findAll();
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
            'last' => ['last'],
            'formSearch' => $formSearch->createView(),
            'form_search_all' => $formSearchAll->createView(),
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
