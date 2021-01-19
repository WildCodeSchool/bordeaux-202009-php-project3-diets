<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\SearchServiceFormType;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service", name="service_")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EntityManagerInterface $entityManager,
                          Request $request,
                          ServiceRepository $serviceRepository): Response
    {
        $formSearch = $this->createForm(SearchServiceFormType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            $services = $serviceRepository->findLikeName($search);
        } else {
            $services = [];
        }


        $service = new Service();
        $formService = $this->createForm(ServiceType::class, $service);
        $formService->handleRequest($request);
        if ($formService->isSubmitted() && $formService->isValid()) {
            $service->setUser($this->getUser());
            $entityManager->persist($service);
            $entityManager->flush();
        }
        $service = $this->getDoctrine()
            ->getRepository(Service::class)
            ->findBy(array(), array('id' => 'desc'));

        return $this->render('service/index.html.twig', [
            'formService' => $formService->createView(),
            'services' => $service,
            'formSearch' => $formSearch->createView(),
            'servicesSearch' => $services,
        ]);
    }
}
