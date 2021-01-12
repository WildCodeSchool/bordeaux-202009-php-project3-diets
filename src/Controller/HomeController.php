<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/card", name="card")
     */
    public function card(): Response
    {
        return $this->render('component/_card.html.twig');
    }
/*
    /**
     * @Route("/connect", name="connect")
     */
    /*
    public function connect(): Response
    {
        return $this->render('home/index_connect.html.twig');
    }
    */

    /**
     * @Route("/inscription", name="register")
     */
    public function register(): Response
    {
        return $this->render('home/index_register.html.twig');
    }
}
