<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\User;
use App\Repository\EventRepository;
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
    public function index(UserRepository $userRepository,
                          EventRepository $eventRepository,
                          ServiceRepository $serviceRepository): Response
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

        return $this->render('admin/index.html.twig', [
            'registered_user_count' => count($registeredUser),
            'registered_user' => $registeredUser,
            'event_for_validation' => $approveEvent,
            'service_for_validation' => $approveService
        ]);
    }

    /**
     * @Route("/admin/event/", methods={"POST"}, name="valid_event")
     */
    public function validAnEvent(Request $request,
                                 EntityManagerInterface $entityManager,
                                 EventRepository $eventRepository): Response
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
    public function validService(Request $request,
                                 EntityManagerInterface $entityManager,
                                 ServiceRepository $serviceRepository): Response
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
    public function deleteUser(Request $request,
                           User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }
}
