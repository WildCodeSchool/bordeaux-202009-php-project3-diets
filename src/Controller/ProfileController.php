<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/show", name="show")
     */
    public function show(): Response
    {
        return $this->render('profile/show.html.twig', [
            '' => '',
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $formService = $this->createForm(ServiceType::class, $service);
        $formService->handleRequest($request);
        if ($formService->isSubmitted() && $formService->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();
        }
        /*$service = $this->getDoctrine()
            ->getRepository(Service::class)
            ->findBy('user' => 'id');*/

        return $this->render('profile/edit.html.twig', [
            'formService' => $formService->createView(),
            'services' => $service,
        ]);
    }
}
