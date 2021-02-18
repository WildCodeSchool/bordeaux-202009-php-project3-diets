<?php

namespace App\Controller;

use App\Entity\EventFormat;
use App\Entity\Expertise;
use App\Entity\Pathology;
use App\Entity\ResourceFormat;
use App\Entity\User;
use App\Form\EventFormatType;
use App\Form\ExpertiseType;
use App\Form\PathologyType;
use App\Form\ResourceFormatType;
use App\Repository\EventFormatRepository;
use App\Repository\EventRepository;
use App\Repository\ExpertiseRepository;
use App\Repository\PathologyRepository;
use App\Repository\RegisteredEventRepository;
use App\Repository\ResourceFormatRepository;
use App\Repository\ResourceRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(
        UserRepository $userRepository,
        EventRepository $eventRepository,
        ServiceRepository $serviceRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        PathologyRepository $pathologyRepository,
        ExpertiseRepository $expertiseRepository,
        ResourceFormatRepository $resourceFormatRepository,
        EventFormatRepository $eventFormatRepository,
        RegisteredEventRepository $registeredEventRepository
    ): Response
    {
        $registeredUser = $userRepository->findBy(
            [
            ],
            [ 'lastname' => 'ASC'
            ]
        );

        $approveEvent = $eventRepository->findBy(
            ['eventIsValidated' => 0]
        );
        $approveService = $serviceRepository->findBy(
            ['serviceIsValidated' => 0]
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

        $expertise = new Expertise();
        $formExpertise = $this->createForm(ExpertiseType::class, $expertise);
        $formExpertise->handleRequest($request);
        if ($formExpertise->isSubmitted() && $formExpertise->isValid()) {
            $entityManager->persist($expertise);
            $entityManager->flush();

            $this->addFlash('success', 'L\'expertise a bien été créée');
        }

        $expertises = $expertiseRepository->findAll();

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


        return $this->render('admin/index.html.twig', [
            'registered_user_count' => count($registeredUser),
            'registered_user' => $registeredUser,
            'event_for_validation' => $approveEvent,
            'service_for_validation' => $approveService,
            'form' => $form->createView(),
            'pathologies' => $pathologies,
            'form_expertise' => $formExpertise->createView(),
            'expertises' => $expertises,
            'form_resource_format' => $formResourceFormat->createView(),
            'formats' => $resourceFormats,
            'form_event_format'=> $formEventFormat->createView(),
            'event_formats' => $eventFormats,
            'registered_events' => $registeredEventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/event/", methods={"POST"}, name="valid_event")
     */
    public function validAnEvent(
        Request $request,
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository
    ): Response
    {
        $event = $eventRepository->find($request->get('event'));
        $event->setEventIsValidated(true);
        $entityManager->persist($event);
        $entityManager->flush();

        $this->addFlash('success', 'L\'évènement a bien été validé');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/service/", methods={"POST"}, name="valid_service")
     *
     */
    public function validService(
        Request $request,
        EntityManagerInterface $entityManager,
        ServiceRepository $serviceRepository
    ): Response
    {
        $service = $serviceRepository->find($request->get('service'));
        $service->setServiceIsValidated(true);
        $entityManager->persist($service);
        $entityManager->flush();

        $this->addFlash('success', 'Service validé');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/{id}", name="admin_delete_user", methods={"DELETE"})
     *
     */
    public function deleteUser(
        Request $request,
        User $user,
        RegisteredEventRepository $registeredEventRepository,
        ResourceRepository $resourceRepository
    ): Response
    {
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
}
