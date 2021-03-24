<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\ResourceFile;
use App\Entity\ResourceFormat;
use App\Entity\User;
use App\Form\ResourcePayingType;
use App\Form\ResourcesAllType;
use App\Form\ResourceType;
use App\Form\SearchResourceType;
use App\Form\VisioPaydType;
use App\Form\VisioType;
use App\Repository\ResourceFormatRepository;
use App\Repository\ResourceRepository;
use App\Repository\UserRepository;
use App\Service\MultiUpload\MultiUploadService;
use App\Service\Publicity\PublicityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        MultiUploadService $multiUploadService,
        UserRepository $userRepository,
        PublicityService $publicityService
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
            if (($newResource->getLink() === null) && ($formResource->get('resourceFiles')->getData() === [])) {
                $this->addFlash('danger', 'Vous avez oublié de joindre des documents ou un lien');
            } else {
                $newResource->setUser($this->getUser());
                $newResource = $multiUploadService->createMultiUploadToResource($formResource, $newResource);
                $entityManager->persist($newResource);
                $entityManager->flush();
            }
        }

        $visio = new Resource();
        $formVisio = $this->createForm(VisioType::class, $visio);
        $formVisio->handleRequest($request);
        if ($formVisio->isSubmitted() && $formVisio->isValid()) {
            if ($visio->getLink() === null) {
                $this->addFlash('danger', 'Vous avez oublié de joindre un lien pour la visioconférence.');
            } else {
                $visio->setUser($this->getUser());
                $identifier = $this->getDoctrine()
                    ->getRepository(ResourceFormat::class)
                    ->findOneBy([
                        'identifier' => 'visioconference'
                    ]);

                $visio->setResourceFormat($identifier);

                $visio = $multiUploadService->createMultiUploadToResource($formVisio, $visio);
                $entityManager->persist($visio);
                $entityManager->flush();
            }
        }

        $visioPayd = new Resource();
        $formVisioPayd = $this->createForm(VisioPaydType::class, $visioPayd);
        $formVisioPayd->handleRequest($request);
        if ($formVisioPayd->isSubmitted() && $formVisioPayd->isValid()) {
            if ($visioPayd->getLink() === null) {
                $this->addFlash('danger', 'Vous avez oublié de joindre un lien pour la visioconférence.');
            } else {
                $visioPayd->setUser($this->getUser());
                $identifier = $this->getDoctrine()
                    ->getRepository(ResourceFormat::class)
                    ->findOneBy([
                        'identifier' => 'visioconference'
                    ]);

                $visioPayd->setResourceFormat($identifier);

                $visioPayd = $multiUploadService->createMultiUploadToResource($formVisioPayd, $visioPayd);
                $entityManager->persist($visioPayd);
                $entityManager->flush();
            }
        }


        $newResourcePayd = new Resource();
        $formResourcePayd = $this->createForm(ResourcePayingType::class, $newResourcePayd);
        $formResourcePayd->handleRequest($request);
        if ($formResourcePayd->isSubmitted() && $formResourcePayd->isValid()) {
            if (($newResourcePayd->getLink() === null) && ($formResourcePayd->get('resourceFiles')->getData() === [])){
                $this->addFlash('danger', 'Vous avez oublié de joindre des documents ou un lien');
            } else {
                $newResourcePayd->setUser($this->getUser());
                $newResourcePayd = $multiUploadService
                    ->createMultiUploadToResource($formResourcePayd, $newResourcePayd);
                $entityManager->persist($newResourcePayd);
                $entityManager->flush();
            }
        }

        $resourcesLastUpdate = $resourceRepository->findBy(
            [
            ],
            [
                'updatedAt' => 'DESC'
            ],
            self::NBRESOURCE
        );

        $resourcesWithNextVisio = $resourceRepository->nextVisio();

        $publicities = $publicityService->addPublicity();
        $companiespublicity = $publicities[0];
        $freelancersPublicity = $publicities[1];

        return $this->render('knowledge/index.html.twig', [
            'resources_last_update' => $resourcesLastUpdate,
            'resources_search' => $resourcesSearch,
            'last' => ['last'],
            'form_resource' => $formResource->createView(),
            'form_search' => $formSearch->createView(),
            'form_search_all' => $formSearchAll->createView(),
            'form_visio' => $formVisio->createView(),
            'path' => 'knowledge_index',
            'form_visio_payd' => $formVisioPayd->createView(),
            'form_resource_payd' => $formResourcePayd->createView(),
            'companies_publicity' => $companiespublicity,
            'freelancers_publicity' => $freelancersPublicity,
            'resources_with_next_visio' => $resourcesWithNextVisio
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

    /**
     * @Route("/resourceFile/{id}", name="delete_resourceFile", methods={"DELETE"})
     *
     */
    public function deleteResourceFile(
        Request $request,
        ResourceFile $resourceFile
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $resourceFile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resourceFile);
            $entityManager->flush();
        }
        return $this->redirectToRoute('knowledge_index');
    }

    /**
     * @Route("/calendrier", name="calendar")
     */
    public function calendar(): Response
    {
        return $this->render('knowledge/calendar.html.twig');
    }

    /**
     * @Route("/jsonCalendar", name="calendar_json")
     */
    public function jsonCalendar(ResourceRepository $resourceRepository)
    {
        $dateVisios = $resourceRepository->selectVisioForCalendar();
        $visios = [];
        foreach ($dateVisios as $dateVisio) {
            $visios[] = [
                //'id' => $dateVisio->getId(),
                'start'  => $dateVisio->getDateStart()->format('Y-m-d H:i'),
                'end' => $dateVisio->getDateEnd()->format('Y-m-d H:i'),
                'title' => $dateVisio->getName(),
                //'description' => $dateVisio->getDescription(),
            ];
        }

        return new JsonResponse($visios, 200);
    }
}
