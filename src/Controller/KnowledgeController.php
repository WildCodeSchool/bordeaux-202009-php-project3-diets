<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\User;
use App\Form\ResourceType;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/knowledge", name="knowledge")
 */
class KnowledgeController extends AbstractController
{
    private const LIMIT = 10;

    /**
     * @Route("/{length}", name="_index")
     */
    public function index(
        string $length,
        Request $request,
        EntityManagerInterface $entityManager,
        ResourceRepository $resourceRepository
    ): Response {
        if ($length === 'last') {
            $length = true ;
            $resource = $resourceRepository->findBy([], ['updatedAt' => 'DESC']);
        } else {
            $length = false ;
            $resource = $resourceRepository->findBy([], ['updatedAt' => 'DESC'], self::LIMIT);
        }
        $newResource = new Resource();
        $formResource = $this->createForm(ResourceType::class, $newResource);
        $formResource->handleRequest($request);
        if ($formResource->isSubmitted() && $formResource->isValid()) {
            $newResource->setUser($this->getUser());
            $entityManager->persist($newResource);
            $entityManager->flush();
        }

        return $this->render('knowledge/index.html.twig', [
            'resources' => $resource,
            'length'    => $length,
            'formResource' => $formResource->createView(),
        ]);
    }
}
