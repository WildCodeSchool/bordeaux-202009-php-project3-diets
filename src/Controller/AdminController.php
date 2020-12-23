<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $userRepository): Response
    {
        $registeredUser = $userRepository->findBy(
            [
            ],
            [ 'lastname' => 'ASC'
            ]
        );
        return $this->render('admin/index.html.twig', [
            'registered_user_count' => count($registeredUser),
            'registered_user' => $registeredUser,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }
}
