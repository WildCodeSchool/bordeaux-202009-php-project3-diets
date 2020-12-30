<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function edit(): Response
    {

        return $this->render('profile/edit.html.twig', [
            '' => '',
        ]);
    }
}
