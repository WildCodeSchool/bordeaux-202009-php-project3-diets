<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/show/{id}", methods={"GET"}, name="show", requirements={"id":"\d+"})
     * @return Response
     */
    public function show(User $user): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'No profile with id : ' . $user->getId() . ' found in user\'s table.'
            );
        } else {
            $userInfos = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(['id' => $user->getId()]);
        }
        //dd($user);
        return $this->render('profile/show.html.twig', [
            'user_infos' => $userInfos,
        ]);
    }

    /**
     * @Route("/edit/{id}", methods={"GET"}, name="edit")
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'No profile with id : ' . $user->getId() . ' found in user\'s table.'
            );
        } else {
            $userInfos = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(['id' => $user->getId()]);
        }
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
            'user_infos' => $userInfos,
        ]);
    }
}
