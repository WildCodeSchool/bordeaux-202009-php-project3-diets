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
     * @Route("/register/event/{id}", name="register_event", methods={"GET","POST"})
     */
    public function registerToEvent(Request $request, EntityManagerInterface $entityManager,
                                  RegisteredEventRepository $registeredEventRepository,
                                  Event $event): Response
    {
        /* Si l'inscription est déjà là, renvoyer directement */
            $registeredEvent = new RegisteredEvent();
            $registeredEvent->setUser($this->getUser());
            $registeredEvent->setEvent($event);
            $registeredEvent->setIsOrganizer('0');
            $entityManager->persist($registeredEvent);
            $entityManager->flush();
        return $this->redirectToRoute('event_index');
    }
}
