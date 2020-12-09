<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KnowledgeController extends AbstractController
{
    /**
     * @Route("/knowledge", name="knowledge")
     */
    public function index(): Response
    {
        return $this->render('knowledge/index.html.twig', [
            'controller_name' => 'KnowledgeController',
        ]);
    }
}