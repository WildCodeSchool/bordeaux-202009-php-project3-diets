<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\SearchEventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event_index")
     */
    public function index(Request $request,
                          EntityManagerInterface $entityManager,
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
        $formSearch = $this->createForm(SearchEventType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            $eventSearch = $eventRepository->findLikeName($search);
        } else {
            $eventSearch = [];
        }

        return $this->render('event/index.html.twig', [
            'form' => $formSearch->createView(),
            'eventsSearch' => $eventSearch,
            'events' => $eventRepository->findAll(),
            'formEvent' => $formEvent->createView(),
        ]);
    }

}
