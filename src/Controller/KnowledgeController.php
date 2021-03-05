<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\ResourceFile;
use App\Entity\User;
use App\Form\ResourcesAllType;
use App\Form\ResourceType;
use App\Form\SearchResourceType;
use App\Repository\ResourceRepository;
use App\Service\MultiUpload\MultiUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/connaissances", name="knowledge_")
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
        EntityManagerInterface $entityManager,
        MultiUploadService $multiUploadService
    ): Response {
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $formSearch->getData()['search'] !== null ?
                $search = $formSearch->getData()['search'] : $search = '';
            $pathology = [];
            $pathologies = $formSearch->getData()['pathology'];
            if ($pathologies !== null) {
                foreach ($pathologies as $patho) {
                    $pathology[] = $patho->getIdentifier() ;
                }
            }
            $formSearch->getData()['format'] !== null ?
                $format = $formSearch->getData()['format']->getIdentifier() : $format = '';
            if (!$search && (empty($pathology)) && !$format) {
                $resourcesSearch = ['last'];
            } elseif (!$search && (empty($pathology)) && $format) {
                $resourcesSearch = $resourceRepository
                    ->searchByFormat($format);
            } elseif (!$search && (!empty($pathology)) && !$format) {
                $resourcesSearch = $resourceRepository
                    ->searchByPathology($pathology);
            } elseif ($search && (empty($pathology)) && !$format) {
                $resourcesSearch = $resourceRepository
                    ->searchLikeName($search);
            } elseif ($search && (empty($pathology)) && !$format) {
                $resourcesSearch = $resourceRepository
                    ->searchByPathologyAndLikeName($pathology, $search);
            } elseif ($search && (empty($pathology)) && $format) {
                $resourcesSearch = $resourceRepository
                    ->searchByFormatAndLikeName($format, $search);
            } elseif (!$search && (!empty($pathology)) && $format) {
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
            $newResource = $multiUploadService->createMultiUploadToResource($formResource, $newResource);
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
            'resources_last_update' => $resourcesLastUpdate,
            'resources_search' => $resourcesSearch,
            'last' => ['last'],
            'form_resource' => $formResource->createView(),
            'form_search' => $formSearch->createView(),
            'form_search_all' => $formSearchAll->createView(),
            'path' => 'knowledge_index',
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteKnowledge(
        Request $request,
        Resource $resource
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $resource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resource);
            $entityManager->flush();
        }
        return $this->redirectToRoute('knowledge_index');
    }
}
