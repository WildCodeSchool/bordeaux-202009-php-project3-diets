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
    * @Route("/ressource", name="ressource")
    */
    public function ressource(): Response
    {

        return $this->render('ressource/index.html.twig', [
        //    'form' => $form->createView(),
        ]);
    }
/*           $form = $this->createForm(eType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($episode);
            $entityManager->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,

        ]);
    }*/
}