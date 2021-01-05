<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\RegisteredEvent;
use App\Entity\User;
use App\Entity\Service;
use App\Form\EventType;
use App\Form\ServiceType;
use App\Form\UserEditType;
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
            $expertises = "";
            foreach ($userInfos[0]->getExpertise() as $expertise) {
                $expertises = $expertises . $expertise->getname() . ', ';
            }
            $expertises = substr($expertises, 0, -2);
        }
        return $this->render('profile/show.html.twig', [
            'user_infos' => $userInfos[0],
            'expertises' => $expertises,
        ]);
    }

    /**
     * @Route("/edit/{id}", methods={"GET", "POST"}, name="edit")
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
            $expertises = "";
            foreach ($userInfos[0]->getExpertise() as $expertise) {
                $expertises = $expertises . $expertise->getname() . ', ';
            }
            $expertises = substr($expertises, 0, -2);
        }

        $formEditUser = $this->createForm(UserEditType::class, $user);
        $formEditUser->handleRequest($request);
        if ($formEditUser->isSubmitted() && $formEditUser->isValid()) {
            if (($user->isVerified() === true) && ($user->getRoles() != ['ROLE_ADMIN'])) {
                $user->setRoles(['ROLE_CONTRIBUTOR']);
                $entityManager->persist($user);
                $entityManager->flush();
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('service_index');
        }

        $service = new Service();
        $formService = $this->createForm(ServiceType::class, $service);
        $formService->handleRequest($request);
        if ($formService->isSubmitted() && $formService->isValid()) {
            $service->setUser($this->getUser());
            $entityManager->persist($service);
            $entityManager->flush();
        }

        $event = new Event();
        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);
        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $registerEvent = new RegisteredEvent();
            $registerEvent->setUser($this->getUser());
            $registerEvent->setEvent($event);
            $registerEvent->setIsOrganizer('1');
            $event->addRegisteredEvent($registerEvent);
            $event->setEventIsValidated('0');
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->render('profile/edit.html.twig', [

            'services' => $service,
            'user_infos' => $userInfos[0],
            'expertises' => $expertises,
            'formEditUser' => $formEditUser->createView(),
            'formService' => $formService->createView(),
            'formEvent' => $formEvent->createView(),
        ]);
    }
}
