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
     * @Route("/show/{id}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(User $user): Response
    {
        if (!$user) {
            return $this->redirectToRoute('ressource');
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
     * @Route("/edit", name="edit")
     */
    public function edit(): Response
    {
        return $this->render('profile/edit.html.twig', [
            '' => '',
        ]);
    }
}
