<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Picture;
use App\Entity\RegisteredEvent;
use App\Form\EventType;
use App\Form\SearchResourceType;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use App\Repository\RegisteredEventRepository;
use App\Repository\UserRepository;
use App\Service\MultiUpload\MultiUploadService;
use App\Service\Publicity\PublicityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenement", name="event_")
 */

class EventController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository,
        RegisteredEventRepository $registeredEventRepository,
        MultiUploadService $multiUploadService,
        UserRepository $userRepository,
        PublicityService $publicityService
    ): Response {

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
        $events = $eventRepository->nextEvent();
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        $eventSearch = [];
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            if (!$search) {
                $events = $eventRepository->nextEvent();
            } else {
                $eventSearch = $eventRepository->findLikeName($search);
            }
        }

        $pictures = $this->getDoctrine()
            ->getRepository(Picture::class)
            ->findBy(['link' => null, 'service' => null]);

        $publicities = $publicityService->addPublicity();
        $companiespublicity = $publicities[0];
        $freelancersPublicity = $publicities[1];

        return $this->render('event/index.html.twig', [
            'form' => $formSearch->createView(),
            'events_search' => $eventSearch,
            'events' => $events,
            'form_event' => $formEvent->createView(),
            'pictures' => $pictures,
            'path' => 'event_index',
            'registered_events' => $registeredEventRepository->findAll(),
            'companies_publicity' => $companiespublicity,
            'freelancers_publicity' => $freelancersPublicity
            ]);
    }

    /**
     * @Route("/supprimer/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteEvent(
        Request $request,
        Event $event,
        RegisteredEventRepository $registeredEventRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $registeredEvents = $registeredEventRepository->findBy(['event' => $event]);
            foreach ($registeredEvents as $registeredEvent) {
                $entityManager->remove($registeredEvent);
            }
            $entityManager->remove($event);
            $entityManager->flush();
        }
        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/picture/{id}", name="delete_picture", methods={"DELETE"})
     *
     */
    public function deletePicture(
        Request $request,
        Picture $picture
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($picture);
            $entityManager->flush();
        }
        return $this->redirectToRoute('event_index');
    }
}
