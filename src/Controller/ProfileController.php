<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Dietetician;
use App\Entity\Event;
use App\Entity\Freelancer;
use App\Entity\Picture;
use App\Entity\RegisteredEvent;
use App\Entity\Resource;
use App\Entity\ResourceFile;
use App\Entity\ResourceFormat;
use App\Entity\Shopping;
use App\Entity\User;
use App\Entity\Service;
use App\Form\CompanyType;
use App\Form\DieteticianType;
use App\Form\EventType;
use App\Form\FreelancerType;
use App\Form\ResourcePayingType;
use App\Form\ResourceType;
use App\Form\ServiceType;
use App\Form\UserEditType;
use App\Form\VisioPaydType;
use App\Form\VisioType;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use App\Repository\ResourceRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Service\MultiUpload\MultiUploadService;
use App\Service\Stripe\StripeService;
use App\Service\Stripe\StripeSubscribeService;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\AssignOp\Mul;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/edition/{id}", methods={"GET", "POST"}, name="edit")
     * @return Response
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        User $user,
        PictureRepository $pictureRepository,
        SessionInterface $session,
        MultiUploadService $multiUploadService,
        StripeSubscribeService $stripeSubscribeService
    ): Response {
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

        $resources = $this->getDoctrine()->getRepository(Resource::class)->findBy(
            [
            'user' => $user->getId()],
            ['updatedAt' => 'desc']
        );
        $resourcesPurchasedCount = [];
        foreach ($resources as $resource) {
            $resourcesPurchased = $this->getDoctrine()->getRepository(Shopping::class)->findBy([
                'name' => $resource->getName()
            ]);
            if (!empty($resourcesPurchased)) {
                $count = count($resourcesPurchased);
                $resourcesPurchasedCount[$resource->getId()] = $count ;
            }
        }


        $eventsOrganized = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->registeredEventOrganized($user);


        /*******************************************************************************************************/
        $userMail = $session->get('user');
        if (empty($userMail)) {
            $userEmail = $user->getEmail();
        }
        $session->set('userEmail', $userEmail);
        /*******************************************************************************************************/

        $newResource = new Resource();
        $formResource = $this->createForm(ResourceType::class, $newResource);
        $formResource->handleRequest($request);
        if ($formResource->isSubmitted() && $formResource->isValid()) {
            if (($newResource->getLink() === null) && ($formResource->get('resourceFiles')->getData() === [])) {
                $this->addFlash('danger', 'Vous avez oublié de joindre des documents ou un lien');
            } else {
                $newResource->setUser($this->getUser());
                $newResource->setPrice(0);
                $newResource = $multiUploadService->createMultiUploadToResource($formResource, $newResource);
                $entityManager->persist($newResource);
                $entityManager->flush();
            }
        }

        $newResourcePayd = new Resource();
        $formResourcePayd = $this->createForm(ResourcePayingType::class, $newResourcePayd);
        $formResourcePayd->handleRequest($request);
        if ($formResourcePayd->isSubmitted() && $formResourcePayd->isValid()) {
            if (($newResourcePayd->getLink() === null) && ($formResourcePayd->get('resourceFiles')->getData() === [])) {
                $this->addFlash('danger', 'Vous avez oublié de joindre des documents ou un lien');
            } else {
                $newResourcePayd->setUser($this->getUser());
                $newResourcePayd = $multiUploadService
                    ->createMultiUploadToResource($formResourcePayd, $newResourcePayd);
                $entityManager->persist($newResourcePayd);
                $entityManager->flush();
            }
        }

        $visio = new Resource();
        $formVisio = $this->createForm(VisioType::class, $visio);
        $formVisio->handleRequest($request);
        if ($formVisio->isSubmitted() && $formVisio->isValid()) {
            if ($visio->getLink() === null) {
                $this->addFlash('danger', 'Vous avez oublié de joindre un lien pour la visioconférence.');
            } else {
                $visio->setUser($this->getUser());
                $identifier = $this->getDoctrine()
                    ->getRepository(ResourceFormat::class)
                    ->findOneBy([
                        'identifier' => 'visioconference'
                    ]);

                $visio->setResourceFormat($identifier);
                $visio->setPrice(0);

                $visio = $multiUploadService->createMultiUploadToResource($formVisio, $visio);
                $entityManager->persist($visio);
                $entityManager->flush();
            }
        }

        $visioPayd = new Resource();
        $formVisioPayd = $this->createForm(VisioPaydType::class, $visioPayd);
        $formVisioPayd->handleRequest($request);
        if ($formVisioPayd->isSubmitted() && $formVisioPayd->isValid()) {
            if ($visioPayd->getLink() === null) {
                $this->addFlash('danger', 'Vous avez oublié de joindre un lien pour la visioconférence.');
            } else {
                $visioPayd->setUser($this->getUser());
                $identifier = $this->getDoctrine()
                    ->getRepository(ResourceFormat::class)
                    ->findOneBy([
                        'identifier' => 'visioconference'
                    ]);

                $visioPayd->setResourceFormat($identifier);

                $visioPayd = $multiUploadService->createMultiUploadToResource($formVisioPayd, $visioPayd);
                $entityManager->persist($visioPayd);
                $entityManager->flush();
            }
        }

        $service = new Service();
        $formService = $this->createForm(ServiceType::class, $service);
        $formService->handleRequest($request);
        if ($formService->isSubmitted() && $formService->isValid()) {
            $service->setUser($this->getUser());
            $service->setServiceIsValidated(false);
            $service = $multiUploadService->createMultiUploadToService($formService, $service);
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
            $event = $multiUploadService->createMultiUploadToEvent($formEvent, $event);
            $entityManager->persist($registerEvent);
            $entityManager->persist($event);
            $entityManager->flush();
        }

        $pictures = $this->getDoctrine()
            ->getRepository(Picture::class)
            ->findBy(['link' => null]);

        $publicKey = $this->getParameter('api_public_key');

        $checkSubscription = $stripeSubscribeService->changeStatusForSubscriber();





        return $this->render('profile/edit.html.twig', [
            'form_visio' => $formVisio->createView(),
            'form_visio_payd' => $formVisioPayd->createView(),
            'events_organized' => $eventsOrganized,
            'services' => $service,
            'user_infos' => $userInfos[0],
            'expertises' => $expertises,
            /*'events_and_participants' => $eventsAndParticipantsArray,*/
            'form_service' => $formService->createView(),
            'form_event' => $formEvent->createView(),
            'resources' => $resources,
            'form_resource' => $formResource->createView(),
            'form_resource_payd' => $formResourcePayd->createView(),
            'pictures' => $pictures,
            'path' => 'profile_edit',
            'public_key' => $publicKey,
            'resources_purchased_count' => $resourcesPurchasedCount
        ]);
    }


    /**
     * @Route("/profil/edition/{id}", methods={"GET", "POST"}, name="profil_edit")
     * @return Response
     */

    public function editProfil(
        Request $request,
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        User $user
    ): Response {
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
                return $this->redirectToRoute('profile_edit', ['id' => $id ]);
            }
        }


        return $this->render('component/_profil_edit.html.twig', [
            'form_edit_user' => $formEditUser->createView(),
            'user' => $user,
        ]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/connaissance/edition/{id}", methods={"GET", "POST"}, name="resource_edit")
     * @return Response
     */

    public function editResource(
        Resource $resource,
        Request $request,
        int $id,
        ResourceRepository $resourceRepository,
        MultiUploadService $multiUploadService
    ): Response {

        $resource = $resourceRepository->findOneBy(['id' => $id]);

        $formEditResource = $this->createForm(ResourceType::class, $resource);
        $formEditResource->handleRequest($request);
        if ($formEditResource->isSubmitted() && $formEditResource->isValid()) {
            $multiUploadService->createMultiUploadToResource($formEditResource, $resource);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('knowledge_index');
        }

        return $this->render('component/_resource_edit.html.twig', [
            'form' => $formEditResource->createView(),
            'resource' => $resource
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/service/edition/{id}", methods={"GET", "POST"}, name="service_edit")
     * @return Response
     */

    public function editService(
        Service $service,
        int $id,
        Request $request,
        ServiceRepository $serviceRepository,
        PictureRepository $pictureRepository,
        MultiUploadService $multiUploadService
    ): Response {
        $service = $serviceRepository->findOneBy(['id' => $id]);

        $formEditService = $this->createForm(ServiceType::class, $service);
        $formEditService->handleRequest($request);
        if ($formEditService->isSubmitted() && $formEditService->isValid()) {
            $multiUploadService->createMultiUploadToService($formEditService, $service);
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
     * @Route("/evenemement/edition/{id}", methods={"GET", "POST"}, name="event_edit")
     * @return Response
     */

    public function editEvent(
        Event $event,
        int $id,
        Request $request,
        EventRepository $eventRepository,
        PictureRepository $pictureRepository,
        MultiUploadService $multiUploadService
    ): Response {

        $event = $eventRepository->findOneBy(['id' => $id]);

        $formEditEvent = $this->createForm(EventType::class, $event);
        $formEditEvent->handleRequest($request);
        if ($formEditEvent->isSubmitted() && $formEditEvent->isValid()) {
            $multiUploadService->createMultiUploadToEvent($formEditEvent, $event);
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
     * @Route("/connaissance/{id}", name="delete_resource", methods={"DELETE"})
     */
    public function deleteResource(
        Request $request,
        Resource $resource
    ): Response {
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
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($service);
            $entityManager->flush();
        }
        return $this->redirectToRoute('service_index');
    }

    /**
     * @Route ("/choix-statut/{id}", name="choice_role")
     */

    public function choiceRole(
        UserRepository $userRepository,
        $id,
        User $user
    ): Response {
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
     * @Route ("/inscription/societe/{id}", name="register_company")
     */

    public function companyRegister(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        $id,
        User $user,
        StripeService $stripeService
    ): Response {
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
                $stripe = $stripeService->createAccount($id);
            }
            $company->setUser($user);
            $entityManager->persist($company);
            $entityManager->flush();
            return $this->redirectToRoute('payment_register_stripe', ['id' => $user->getId() ]);
        }

        return $this->render('component/_register_company.html.twig', [
            'form_company' => $form->createView(),

        ]);
    }

    /**
     * @Route ("/inscription/dieteticien/{id}", name="register_dietetician")
     */
    public function dieteticianRegister(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        $id,
        User $user,
        StripeService $stripeService
    ): Response {
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


        $dietetician = new Dietetician();
        $form = $this->createForm(DieteticianType::class, $dietetician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRoles() === ['ROLE_USER']) {
                $user->setRoles(['ROLE_DIETETICIAN']);
                $user->setIsVerified(true);
                $entityManager->persist($user);
                $entityManager->flush();
                //$stripe = $stripeService->createAccount($id);
            }
            $dietetician->setUser($user);
            $entityManager->persist($dietetician);
            $entityManager->flush();
            //return $this->redirectToRoute('payment_register_stripe', ['id' => $user->getId() ]);
            return $this->redirectToRoute('profile_edit', ['id' => $user->getId() ]);
        }






        return $this->render('component/_register_dietetician.html.twig', [
            'form_dietetician' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/inscription/auto-entrepreneur/{id}", name="register_freelancer")
     */
    public function freelancerRegister(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        $id,
        User $user,
        StripeService $stripeService
    ): Response {
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


        $freelancer = new Freelancer();
        $form = $this->createForm(FreelancerType::class, $freelancer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRoles() === ['ROLE_USER']) {
                $user->setRoles(['ROLE_FREELANCER']);
                $user->setIsVerified(true);
                $entityManager->persist($user);
                $entityManager->flush();
                $stripe = $stripeService->createAccount($id);
            }
            $freelancer->setUser($user);
            $entityManager->persist($freelancer);
            $entityManager->flush();
            return $this->redirectToRoute('payment_register_stripe', ['id' => $user->getId() ]);
        }

        return $this->render('component/_register_freelancer.html.twig', [
            'form_freelancer' => $form->createView(),

        ]);
    }

    /**
     * @Route("/show/{id}", methods={"GET", "POST"}, name="show")
     * @return Response
     */
    public function show(User $user, $id): Response
    {

        $userInfos = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        return $this->render('profile/show.html.twig', [
            'infos' => $userInfos,
        ]);
    }
}
