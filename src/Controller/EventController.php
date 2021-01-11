<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\RegisteredEvent;
use App\Form\EventType;
use App\Form\RegisterType;
use App\Repository\EventRepository;
use App\Repository\RegisteredEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event_index")
     */
    public function index(Request $request, EntityManagerInterface $entityManager,
                          EventRepository $eventRepository): Response
    {
        $event = new Event();
        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);
        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $event->setEventIsValidated('0');
            $entityManager->persist($event);
            $entityManager->flush();
        }

        $eventsAndOrganizers = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->findEventsAndOrganizers();
        $eventsAndOrganizersArray= [];
        foreach($eventsAndOrganizers as $eventAndOrganizer) {
            $eventsAndOrganizersArray[$eventAndOrganizer->getEvent()->getId()] = $eventAndOrganizer->getUser()->getId();
        }

        if (isset($_POST['eventId'])) {
            $eventId = $_POST['eventId'];
            return $this->redirectToRoute('register_event', array('id' => $eventId));
        }

            return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
            'formEvent' => $formEvent->createView(),
            'events_and_organizers' => $eventsAndOrganizersArray,
        ]);
    }

}