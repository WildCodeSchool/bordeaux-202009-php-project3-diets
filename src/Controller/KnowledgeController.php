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

/**
 * @Route("/knowledge", name="knowledge_")
 */

class KnowledgeController extends AbstractController
{
    private const NBRESOURCE = 12;

    /**
     * @Route("/", name="index")
     */
    public function index(
        Request $request,
        ResourceRepository $resourceRepository,
        EntityManagerInterface $entityManager
    ): Response
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

        $newResource = new Resource();
        $formResource = $this->createForm(ResourceType::class, $newResource);
        $formResource->handleRequest($request);
        if ($formResource->isSubmitted() && $formResource->isValid()) {
            $newResource->setUser($this->getUser());
            $entityManager->persist($newResource);
            $entityManager->flush();
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
            'formResource' => $formResource->createView(),
            'formSearch' => $formSearch->createView(),
            'form_search_all' => $formSearchAll->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteKnowledge(
        Request $request,
        Resource $resource
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $resource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resource);
            $entityManager->flush();
        }
        return $this->redirectToRoute('knowledge_index');
    }
}
