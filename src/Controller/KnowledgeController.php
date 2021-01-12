<?php

namespace App\Controller;

use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(string $length, ResourceRepository $resourceRepository): Response
    {
        if ($length === 'last') {
            $length = true ;
            $resource = $resourceRepository->findBy([], ['updatedAt'=> 'ASC']);
        } else {
            $length = false ;
            $resource = $resourceRepository->findBy([], ['updatedAt'=> 'ASC'], self::LIMIT );
        }

        return $this->render('knowledge/index.html.twig', [
            'resources' => $resource,
            'length'    => $length,
        ]);
    }

}
