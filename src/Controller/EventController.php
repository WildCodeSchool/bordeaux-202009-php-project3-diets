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
        PictureRepository $pictureRepository
    ): Response
    {
        $event = new Event();
        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);
        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $registerEvent = new RegisteredEvent();
            $registerEvent->setUser($this->getUser());
            $registerEvent->setEvent($event);
            $registerEvent->setIsOrganizer( true);
            $event->setEventIsValidated(false);
            $entityManager->persist($registerEvent);
            $entityManager->persist($event);
            $entityManager->flush();
        }
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        $eventSearch = [];
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            $eventSearch = $eventRepository->findLikeName($search);
        }

        /*$eventsAndOrganizers = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->findBy(['isOrganizer' => true]);
        $eventsAndOrganizersArray = [];
        foreach ($eventsAndOrganizers as $eventAndOrganizer) {
            $eventsAndOrganizersArray[$eventAndOrganizer->getEvent()->getId()] = $eventAndOrganizer->getUser();
        }

        $eventsAndParticipants = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->findBy(['isOrganizer' => false]);
        $eventsAndParticipantsArray = [];
        foreach ($eventsAndParticipants as $eventAndParticipant) {
                $eventsAndParticipantsArray[$eventAndParticipant->getEvent()->getId()][] =
                $eventAndParticipant->getUser();
        }

        if (isset($_POST['eventIdRegister'])) {
            $eventId = $_POST['eventIdRegister'];
            return $this->redirectToRoute('register_event', array('id' => $eventId));
        }

        if ($this->isCsrfTokenValid('delete-registeredEvent', $request->request->get('_token'))) {
            $eventId = $_POST['eventIdUnregister'];
            return $this->redirectToRoute('unregister_event', array('id' => $eventId));
        }*/

        $events = $eventRepository->nextEvent();

        return $this->render('event/index.html.twig', [
            'form' => $formSearch->createView(),
            'eventsSearch' => $eventSearch,
            'events' => $events,
            'formEvent' => $formEvent->createView(),
            /*'events_and_organizers'   => $eventsAndOrganizersArray,
            'events_and_participants' => $eventsAndParticipantsArray,*/
                'pictures'            => $pictureRepository->findAll(),
            ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteEvent(
        Request $request,
        Event $event,
        RegisteredEventRepository $registeredEventRepository
    ): Response
    {
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
