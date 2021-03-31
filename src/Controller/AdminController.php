<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\EventFormat;
use App\Entity\Pathology;
use App\Entity\ResourceFormat;
use App\Entity\Specialization;
use App\Entity\User;
use App\Form\EventFormatType;
use App\Form\PathologyType;
use App\Form\ResourceFormatType;
use App\Form\SpecializationType;
use App\Repository\DieteticianRepository;
use App\Repository\EventFormatRepository;
use App\Repository\EventRepository;
use App\Repository\PathologyRepository;
use App\Repository\RegisteredEventRepository;
use App\Repository\ResourceFormatRepository;
use App\Repository\ResourceRepository;
use App\Repository\ServiceRepository;
use App\Repository\ShoppingRepository;
use App\Repository\SpecializationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/administrateur", name="admin")
     */
    public function index(
        UserRepository $userRepository,
        EventRepository $eventRepository,
        ServiceRepository $serviceRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        PathologyRepository $pathologyRepository,
        ResourceFormatRepository $resourceFormatRepository,
        EventFormatRepository $eventFormatRepository,
        RegisteredEventRepository $registeredEventRepository,
        ResourceRepository $resourceRepository,
        SpecializationRepository $specializationRepository,
        DieteticianRepository $dieteticianRepository
    ): Response {
        $registeredUser = $userRepository->findAll();

        $approveEvent = $eventRepository->findBy(
            ['eventIsValidated' => 0]
        );
        $approveService = $serviceRepository->findBy(
            ['serviceIsValidated' => 0]
        );
        $approveResource = $resourceRepository->findBy(
            ['isValidated' => 0]
        );

        $pathology = new Pathology();
        $form = $this->createForm(PathologyType::class, $pathology);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pathology->setIdentifier($pathology->getName());
            $entityManager->persist($pathology);
            $entityManager->flush();

            $this->addFlash('success', 'La pathologie a bien été créée');
        }

        $pathologies = $pathologyRepository->findAll();

        $resourceFormat = new ResourceFormat();
        $formResourceFormat = $this->createForm(ResourceFormatType::class, $resourceFormat);
        $formResourceFormat->handleRequest($request);
        if ($formResourceFormat->isSubmitted() && $formResourceFormat->isValid()) {
            $resourceFormat->setIdentifier($resourceFormat->getFormat());
            $entityManager->persist($resourceFormat);
            $entityManager->flush();

            $this->addFlash('success', 'La format pour les ressources a bien été créé');
        }

        $resourceFormats = $resourceFormatRepository->findAll();

        $eventFormat = new EventFormat();

        $formEventFormat = $this->createForm(EventFormatType::class, $eventFormat);
        $formEventFormat->handleRequest($request);
        if ($formEventFormat->isSubmitted() && $formEventFormat->isValid()) {
            $eventFormat->setIdentifier($eventFormat->getFormat());
            $entityManager->persist($eventFormat);
            $entityManager->flush();

            $this->addFlash('success', 'La format pour les évènements a bien été créé');
        }

        $eventFormats = $eventFormatRepository->findAll();


        $specialization = new Specialization();
        $formSpecialization = $this->createForm(SpecializationType::class, $specialization);
        $formSpecialization->handleRequest($request);
        if ($formSpecialization->isSubmitted() && $formSpecialization->isValid()) {
            $entityManager->persist($specialization);
            $entityManager->flush();

            $this->addFlash('success', 'La nouvelle spécialisation a bien été créée');
        }

        $specializations = $specializationRepository->findAll();

        $allResourceFormats = [];
        $allUsedResourceFormat = [];
        $allEventFormats = [];
        $allUsedEventFormat = [];

        foreach ($resourceFormats as $allResourceFormat) {
            $allResourceFormats[] = $allResourceFormat->getFormat();
        }
        $usedResourceFormats = $resourceRepository->verifyResourceFormatUsed($resourceFormats);
        foreach ($usedResourceFormats as $usedResourceFormat) {
            $allUsedResourceFormat[] = $usedResourceFormat->getResourceFormat()->getFormat();
        }
        $notUsedResourceFormats = array_diff($allResourceFormats, $allUsedResourceFormat);

        foreach ($eventFormats as $alleventFormat) {
            $allEventFormats[] = $alleventFormat->getFormat();
        }
        $usedEventFormats = $eventRepository->verifyEventFormatUsed($eventFormats);
        foreach ($usedEventFormats as $usedEventFormat) {
            $allUsedEventFormat[] = $usedEventFormat->getEventFormat()->getFormat();
        }
        $notUsedEventFormats = array_diff($allEventFormats, $allUsedEventFormat);

        return $this->render('admin/index.html.twig', [
            'registered_user_count' => count($registeredUser),
            'registered_user' => $registeredUser,
            'event_for_validation' => $approveEvent,
            'service_for_validation' => $approveService,
            'form' => $form->createView(),
            'pathologies' => $pathologies,
            'form_resource_format' => $formResourceFormat->createView(),
            'formats' => $resourceFormats,
            'form_event_format' => $formEventFormat->createView(),
            'event_formats' => $eventFormats,
            'registered_events' => $registeredEventRepository->findAll(),
            'events' => $eventRepository->findAll(),
            'services' => $serviceRepository->findAll(),
            'notUsedResourceFormats' => $notUsedResourceFormats,
            'notUsedEventFormats' => $notUsedEventFormats,
            'specializations' => $specializations,
            'form_specialization' => $formSpecialization->createView(),
            'resource_for_validation' => $approveResource,
            'resources' => $resourceRepository->findAllValidated()
        ]);
    }

    /**
     * @Route("/administrateur/evenement/", methods={"POST"}, name="valid_event")
     */
    public function validAnEvent(
        Request $request,
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository
    ): Response {
        $event = $eventRepository->find($request->get('event'));
        $event->setEventIsValidated(true);
        $entityManager->persist($event);
        $entityManager->flush();

        $this->addFlash('success', 'L\'évènement a bien été validé');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/administrateur/service/", methods={"POST"}, name="valid_service")
     *
     */
    public function validService(
        Request $request,
        EntityManagerInterface $entityManager,
        ServiceRepository $serviceRepository
    ): Response {
        $service = $serviceRepository->find($request->get('service'));
        $service->setServiceIsValidated(true);
        $entityManager->persist($service);
        $entityManager->flush();

        $this->addFlash('success', 'Service validé');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/administrateur/resource/", methods={"POST"}, name="valid_resource")
     *
     */
    public function validResource(
        Request $request,
        EntityManagerInterface $entityManager,
        ResourceRepository $resourceRepository
    ): Response {
        $resource = $resourceRepository->find($request->get('resource'));
        $resource->setIsValidated(true);
        $entityManager->persist($resource);
        $entityManager->flush();

        $this->addFlash('success', 'Ressource validée');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/administrateur/{id}", name="admin_delete_user", methods={"DELETE"})
     *
     */
    public function deleteUser(
        Request $request,
        User $user,
        RegisteredEventRepository $registeredEventRepository,
        ResourceRepository $resourceRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $registeredEvents = $registeredEventRepository->findBy(['user' => $user]);
            $resources = $resourceRepository->findBy(['user' => $user]);
            foreach ($registeredEvents as $registeredEvent) {
                $entityManager->remove($registeredEvent);
            }
            foreach ($resources as $resource) {
                $entityManager->remove($resource);
            }
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/administrateur/message", name="admin_message")
     */

    public function messagePaginator(Request $request, PaginatorInterface $paginator)
    {
        $data = $this->getDoctrine()->getRepository(Contact::class)->findBy([], ['createdAt' => 'desc']);

        $messages = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/message.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/administrateur/message/{id}", name="admin_message_delete", methods={"DELETE"})
     */


    public function deleteMessage(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_message');
    }

    /**
     * @Route("/supprimer/pathologie/{id}", name="admin_delete_pathology", methods={"DELETE"})
     *
     */
    public function deletePathology(
        Request $request,
        Pathology $pathology
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $pathology->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pathology);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/supprimer/formatRessource/{id}", name="admin_delete_resourceFormat", methods={"DELETE"})
     *
     */
    public function deleteResourceFormat(
        Request $request,
        ResourceFormat $resourceFormat
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $resourceFormat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resourceFormat);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/supprimer/formatEvenement/{id}", name="admin_delete_eventFormat", methods={"DELETE"})
     *
     */
    public function deleteEventFormat(
        Request $request,
        EventFormat $eventFormat
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $eventFormat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eventFormat);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/supprimer/specialisation/{id}", name="admin_delete_specialization", methods={"DELETE"})
     *
     */
    public function deleteSpecialization(
        Request $request,
        Specialization $specialization
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $specialization->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($specialization);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }


    /**
     * @Route("/administrateur/shopping/", name="admin_shopping")
     */
    public function shopping(
        ShoppingRepository $shoppingRepository
    ): Response {

        $shopping = $shoppingRepository->findBy(['type' => 'achat']);
        $freelancerSubscription = $shoppingRepository->findBy(['type' => 'Abonnement Freelancer']);
        $companySubscription = $shoppingRepository->findBy(['type' => 'Abonnement Société']);

        $freelancerNumberSubscription = 0;
        foreach ($freelancerSubscription as $subscriptionFreelancer) {
            if ($subscriptionFreelancer->getAmount() === '20') {
                $freelancerNumberSubscription++;
            } else {
                $freelancerNumberSubscription--;
            }
        }

        $companyNumberSubscription = 0;
        foreach ($companySubscription as $subscriptionCompany) {
            if ($subscriptionCompany->getAmount() === '50') {
                $companyNumberSubscription++;
            } else {
                $companyNumberSubscription--;
            }
        }

        return $this->render('admin/shopping.html.twig', [
            'shopping' => $shopping,
            'freelancer_subscriptions' => $freelancerSubscription,
            'company_subscriptions' => $companySubscription,
            'freelancer_number_subscription' => $freelancerNumberSubscription,
            'company_number_subscription' =>    $companyNumberSubscription
        ]);
    }
}
