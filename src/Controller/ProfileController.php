<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Event;
use App\Entity\Picture;
use App\Entity\RegisteredEvent;
use App\Entity\Resource;
use App\Entity\User;
use App\Entity\Service;
use App\Form\CompanyType;
use App\Form\EventType;
use App\Form\PictureType;
use App\Form\ResourceType;
use App\Form\ServiceType;
use App\Form\UserEditType;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use App\Repository\ResourceRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        if ($this->getUser()->getId() !== $userInfos[0]->getId()) {
            throw $this->createNotFoundException(
                'It is not the profile page of the user ' . $this->getUser()->getId() . '.'
            );
        }

        $resources = $this->getDoctrine()->getRepository(Resource::class)->findBy([
            'user' => $user->getId()],
            ['updatedAt' => 'desc']
        );

        $eventsOrganized = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->registeredEventOrganized($user);




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
            $service->setServiceIsValidated(false);
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
            $registerEvent->setIsOrganizer(true);
            $event->setEventIsValidated(false);
            $entityManager->persist($registerEvent);
            $entityManager->persist($event);
            $entityManager->flush();
        }

        /*dd($eventsOrganized);*/

        return $this->render('profile/edit.html.twig', [
            'events_organized' => $eventsOrganized,
            'services' => $service,
            'user_infos' => $userInfos[0],
            'expertises' => $expertises,
            /*'events_and_participants' => $eventsAndParticipantsArray,*/
            'form_service' => $formService->createView(),
            'form_event' => $formEvent->createView(),
            'resources' => $resources,
            'form_resource' => $formResource->createView(),
            'pictures' => $pictureRepository->findAll(),
            'path' => 'profile_edit',
        ]);
    }


    /**
     * @Route("/profil/edit/{id}", methods={"GET", "POST"}, name="profil_edit")
     * @return Response
     */

    public function editProfil(Request $request,
                               int $id,
                               UserRepository $userRepository,
                               EntityManagerInterface $entityManager,
                               User $user): Response
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
        if ($this->getUser()->getId() !== $userInfos[0]->getId()) {
            throw $this->createNotFoundException(
                'Vous ne pouvez pas accéder à cette page' . $this->getUser()->getId() . '.'
            );
        }
        $user = $userRepository->findOneBy(['id' => $id]);

        $formEditUser = $this->createForm(UserEditType::class, $user);
        $formEditUser->handleRequest($request);
        if ($formEditUser->isSubmitted() && $formEditUser->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($user->getRoles() === ['ROLE_USER']) {
                return $this->redirectToRoute('profile_choice_role', ['id' => $id]);
            } else {
                return $this->redirectToRoute('ressource_index');
            }
        }

        return $this->render('component/_profil_edit.html.twig', [
            'form_edit_user' => $formEditUser->createView(),
            'user' => $user,
        ]);

    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/resource/edit/{id}", methods={"GET", "POST"}, name="resource_edit")
     * @return Response
     */

    public function editResource(Resource $resource,
                                 Request $request,
                                 int $id,
                                 ResourceRepository $resourceRepository): Response
    {

        $resource = $resourceRepository->findOneBy(['id' => $id]);

        $formEditResource = $this->createForm(ResourceType::class, $resource);
        $formEditResource->handleRequest($request);
        if ($formEditResource->isSubmitted() && $formEditResource->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('knowledge_index', [
            ]);
        }

        return $this->render('component/_resource_edit.html.twig', [
            'form' => $formEditResource->createView(),
            'resource' => $resource
        ]);

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/service/edit/{id}", methods={"GET", "POST"}, name="service_edit")
     * @return Response
     */

    public function editService(Service $service,
                                int $id,
                                Request $request,
                                ServiceRepository $serviceRepository,
                                PictureRepository $pictureRepository): Response
    {
        $service = $serviceRepository->findOneBy(['id' => $id]);

        $formEditService = $this->createForm(ServiceType::class, $service);
        $formEditService->handleRequest($request);
        if ($formEditService->isSubmitted() && $formEditService->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('service_index');
        }

        return $this->render('component/_edit_service.html.twig', [
            'form' => $formEditService->createView(),
            'service' => $service,
            'pictures' => $pictureRepository->findAll(),

        ]);

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/event/edit/{id}", methods={"GET", "POST"}, name="event_edit")
     * @return Response
     */

    public function editEvent(Event $event,
                              int $id,
                              Request $request,
                              EventRepository $eventRepository,
                              PictureRepository $pictureRepository): Response
    {

        $event = $eventRepository->findOneBy(['id' => $id]);

        $formEditEvent = $this->createForm(EventType::class, $event);
        $formEditEvent->handleRequest($request);
        if ($formEditEvent->isSubmitted() && $formEditEvent->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('event_index');
        }

        return $this->render('component/_event_edit.html.twig', [
            'form' => $formEditEvent->createView(),
            'event' => $event,
            'pictures' => $pictureRepository->findAll(),
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

    /**
     * @Route("/service/{id}", name="delete_service", methods={"DELETE"})
     */
    public function deleteService(
        Request $request,
        Service $service
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($service);
            $entityManager->flush();
        }
        return $this->redirectToRoute('service_index');
    }

    /**
     * @Route ("/choiceStatus/{id}", name="choice_role")
     */

    public function choiceRole(UserRepository $userRepository,
                               $id,
                               User $user): Response
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
        if ($this->getUser()->getId() !== $userInfos[0]->getId()) {
            throw $this->createNotFoundException(
                'Vous ne pouvez pas accéder à cette page' . $this->getUser()->getId() . '.'
            );
        }

        $user = $userRepository->findOneBy(['id' => $id]);

        return $this->render('profile/choice_role.html.twig', [
            'user' => $user
        ]);

    }

    /**
     * @Route ("/register/company/{id}", name="register_company")
     */

    public function companyRegister(Request $request,
                                    EntityManagerInterface $entityManager,
                                    UserRepository $userRepository,
                                    $id,
                                    User $user): Response
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
        if ($this->getUser()->getId() !== $userInfos[0]->getId()) {
            throw $this->createNotFoundException(
                'Vous ne pouvez pas accéder à cette page' . $this->getUser()->getId() . '.'
            );
        }

        $user = $userRepository->findOneBy(['id' => $id]);

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRoles() === ['ROLE_USER']) {
                $user->setRoles(['ROLE_COMPANY']);
                $user->setIsVerified(true);
                $entityManager->persist($user);
                $entityManager->flush();
            }
            $company->setUser($user);
            $entityManager->persist($company);
            $entityManager->flush();
            return $this->redirectToRoute('ressource_index');
        }

        return $this->render('component/_register_company.html.twig', [
            'form_company' => $form->createView(),

        ]);





    }
}
