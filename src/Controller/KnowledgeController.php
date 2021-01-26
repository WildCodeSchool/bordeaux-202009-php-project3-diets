<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\User;
use App\Form\ResourcesAllType;
use App\Form\ResourceType;
use App\Form\SearchResourceType;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class KnowledgeController extends AbstractController
{
    /*private const LIMIT = 10;*/
    private const NBRESOURCE = 12;

    /**
     * @Route("/knowledge", name="knowledge")
     */
    public function index(Request $request, ResourceRepository $resourceRepository): Response
    {
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $formSearch->getData()['search'] !== null ?
                $search = $formSearch->getData()['search'] : $search = '';
            $formSearch->getData()['pathology'] !== null ?
                $pathology = $formSearch->getData()['pathology']->getIdentifier() : $pathology = '';
            $formSearch->getData()['format'] !== null ?
                $format = $formSearch->getData()['format']->getIdentifier() : $format = '';
            if (!$search && !$pathology && !$format) {
                $resourcesSearch = ['last'];
            } elseif (!$search && !$pathology && $format) {
                $resourcesSearch = $resourceRepository
                    ->searchByFormat($format);
            } elseif (!$search && $pathology && !$format) {
                $resourcesSearch = $resourceRepository
                    ->searchByPathology($pathology);
            } elseif ($search && !$pathology && !$format) {
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
                    ->searchByPathologyFormatAndLikeName($pathology, $format, $search);
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

        $resourcesLastUpdate = $resourceRepository->findBy(
            [
            ],
            [
                'updatedAt' => 'DESC'
            ],
            self::NBRESOURCE
        );

        return $this->render('knowledge/index.html.twig', [
        'resourcesLastUpdate' => $resourcesLastUpdate,
            'resourcesSearch' => $resourcesSearch,
            'last' => ['last'],
            'formSearch' => $formSearch->createView(),
            'form_search_all' => $formSearchAll->createView(),
        ]);
    }
}
