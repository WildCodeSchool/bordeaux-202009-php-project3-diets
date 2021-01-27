<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Picture;
use App\Entity\RegisteredEvent;
use App\Form\EventType;
use App\Form\PictureType;
use App\Form\SearchEventType;
use App\Form\RegisterType;
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

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event_index")
     */
    public function index(Request $request, EntityManagerInterface $entityManager,
                          EventRepository $eventRepository, PictureRepository $pictureRepository): Response
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
        $formSearch = $this->createForm(SearchEventType::class);
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

        return $this->render('event/index.html.twig', [
            'form' => $formSearch->createView(),
            'eventsSearch' => $eventSearch,
            'events' => $eventRepository->findBy(array(), array('dateStart' => 'desc')),
            'formEvent'               => $formEvent->createView(),
            /*'events_and_organizers'   => $eventsAndOrganizersArray,
            'events_and_participants' => $eventsAndParticipantsArray,*/
                'pictures'            => $pictureRepository->findAll(),
            ]);
    }
}
