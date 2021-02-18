<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Picture;
use App\Entity\RegisteredEvent;
use App\Form\EventType;
use App\Form\PictureType;
use App\Form\RegisterType;
use App\Form\SearchResourceType;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use App\Repository\RegisteredEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
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
        PictureRepository $pictureRepository,
        RegisteredEventRepository $registeredEventRepository): Response
    {

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


        return $this->render('event/index.html.twig', [
            'form' => $formSearch->createView(),
            'events_search' => $eventSearch,
            'events' => $events,
            'form_event' => $formEvent->createView(),
            'pictures' => $pictureRepository->findAll(),
            'path' => 'event_index',
            'registered_events' => $registeredEventRepository->findAll(),
            ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteEvent(
        Request $request,
        Event $event,
        RegisteredEventRepository $registeredEventRepository): Response {
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
}
