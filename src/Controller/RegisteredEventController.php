<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\RegisteredEvent;
use App\Repository\RegisteredEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisteredEventController extends AbstractController
{
    /**
     * @Route("/inscription/evenement/{id}", name="register_event", methods={"GET","POST"})
     */
    public function registerToEvent(Request $request, EntityManagerInterface $entityManager,
                                  RegisteredEventRepository $registeredEventRepository,
                                  Event $event): Response
    {
            $registeredEvent = new RegisteredEvent();
            $registeredEvent->setUser($this->getUser());
            $registeredEvent->setEvent($event);
            $registeredEvent->setIsOrganizer('0');
            $entityManager->persist($registeredEvent);
            $entityManager->flush();
        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/desinscription/evenement/{id}", name="unregister_event", methods={"GET","POST"})
     */
    public function unregisterFromEvent(Request $request, EntityManagerInterface $entityManager,
                                    RegisteredEventRepository $registeredEventRepository,
                                    Event $event): Response
    {
        $registeredEvent = $this->getDoctrine()
            ->getRepository(RegisteredEvent::class)
            ->findRegisteredEvent($event, $this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($registeredEvent);
        $entityManager->flush();

        return $this->redirectToRoute('event_index');
    }
}
