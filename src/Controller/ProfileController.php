<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Picture;
use App\Entity\RegisteredEvent;
use App\Entity\Resource;
use App\Entity\User;
use App\Entity\Service;
use App\Form\EventType;
use App\Form\PictureType;
use App\Form\ResourceType;
use App\Form\ServiceType;
use App\Form\UserEditType;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use App\Repository\ResourceRepository;
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
    public function show(User $user, EventRepository $eventRepository): Response
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

            $eventsById = $eventRepository->find(['id' => $user->getId()]);
        }
        return $this->render('profile/show.html.twig', [
            'user_infos' => $userInfos[0],
            'expertises' => $expertises,
            'events' => $eventsById,
        ]);
    }

    /**
     * @Route("/edit/{id}", methods={"GET", "POST"}, name="edit")
     * @return Response
     */
    public function edit(Request $request,
                         EntityManagerInterface $entityManager,
                         User $user,
                         PictureRepository $pictureRepository): Response
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

        $resources = $this->getDoctrine()->getRepository(Resource::class)->findBy(['user' => $user->getId()]);

        $formEditUser = $this->createForm(UserEditType::class, $user);
        $formEditUser->handleRequest($request);
        if ($formEditUser->isSubmitted() && $formEditUser->isValid()) {
            if ($user->getRoles() != ['ROLE_ADMIN']) {
                $user->setRoles(['ROLE_CONTRIBUTOR']);
                $user->isVerified(true);
                $entityManager->persist($user);
                $entityManager->flush();
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('service_index');
        }

        $eventsOrganized = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->registeredEventOrganized($user);



        $eventsAndParticipants = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->findBy(['isOrganizer' => false]);
        $eventsAndParticipantsArray = [];
        foreach ($eventsAndParticipants as $eventAndParticipant) {
            $eventsAndParticipantsArray[$eventAndParticipant->getEvent()->getId()][] =
                $eventAndParticipant->getUser();
        }


        $newResource = new Resource();
        $formResource = $this->createForm(ResourceType::class, $newResource);
        $formResource->handleRequest($request);
        if ($formResource->isSubmitted() && $formResource->isValid()) {
            $newResource->setUser($this->getUser());
            $entityManager->persist($newResource);
            $entityManager->flush();
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
            $event->setEventIsValidated('0');
            $entityManager->persist($registerEvent);
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->render('profile/edit.html.twig', [
            'events_organized' => $eventsOrganized,
            'services' => $service,
            'user_infos' => $userInfos[0],
            'expertises' => $expertises,
            'events_and_participants' => $eventsAndParticipantsArray,
            'formEditUser' => $formEditUser->createView(),
            'formService' => $formService->createView(),
            'formEvent' => $formEvent->createView(),
            'resources' => $resources,
            'formResource' => $formResource->createView(),
            'pictures' => $pictureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/resource/edit/{id}", methods={"GET", "POST"}, name="resource_edit")
     * @return Response
     */

    public function editResource(Resource $resource,
                                 Request $request,
                                 EntityManagerInterface $entityManager,
                                 int $id,
                                 ResourceRepository $resourceRepository): Response
    {
        $resource = $resourceRepository->findOneBy(['id' => $id]);
        $formEditResource = $this->createForm(ResourceType::class, $resource);
        $formEditResource->handleRequest($request);
        if ($formEditResource->isSubmitted() && $formEditResource->isValid()) {
            $entityManager->persist($resource);
            $entityManager->flush();
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->render('profile/resource_edit.html.twig', [
            'form' => $formEditResource->createView(),
        ]);


    }

    /**
     * @Route("/ressource/{id}", name="delete_resource", methods={"DELETE"})
     */
    public function deleteResource(Request $request,
                                   Resource $resource): Response
    {
        if ($this->isCsrfTokenValid('delete' . $resource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resource);
            $entityManager->flush();
        }
        return $this->redirectToRoute('ressource_index');
    }
}
