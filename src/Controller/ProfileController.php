<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     *
     * @Route("/show/{id}", method={"GET"}, name="show")
     * @return Response
     */
    public function show(User $user): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'No user with id : ' . $user->getId() . ' found in user\'s table.'
            );
        } else {
            $userInfos = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(['id' => $user->getId()]);
        }
        return $this->render('profile/show.html.twig', [
            'user' => $userInfos,
        ]);
    }

    /**
     *
     * @Route("/edit/{id}", method={"GET"}, name="edit")
     * @return Response
     */
    public function edit(User $user): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'No user with id : ' . $user->getId() . ' found in user\'s table.'
            );
        } else {
            $userInfos = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(['id' => $user->getId()]);
        }
        return $this->render('profile/show.html.twig', [
            'user' => $userInfos,
        ]);
    }
}
