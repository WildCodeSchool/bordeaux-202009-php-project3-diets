<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\SearchResourceType;
use App\Form\ServiceType;
use App\Repository\PictureRepository;
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
                          ServiceRepository $serviceRepository,
                          PictureRepository $pictureRepository): Response {
        $formSearch = $this->createForm(SearchResourceType::class);
        $formSearch->handleRequest($request);

        $services = [];
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            if (!$search) {
                $services = $serviceRepository->findAll();
            } else {
                $services = $serviceRepository->findLikeName($search);
            }
        }


        $service = new Service();
        $formService = $this->createForm(ServiceType::class, $service);
        $formService->handleRequest($request);
        if ($formService->isSubmitted() && $formService->isValid()) {
            $service->setUser($this->getUser());
            $entityManager->persist($service);
            $entityManager->flush();
            $this->redirectToRoute('service_index');
        }
        $service = $this->getDoctrine()
            ->getRepository(Service::class)
            ->findBy(array(), array('id' => 'desc'));

        return $this->render('service/index.html.twig', [
            'form_service' => $formService->createView(),
            'services' => $service,
            'form_search' => $formSearch->createView(),
            'services_search' => $services,
            'pictures' => $pictureRepository->findAll(),
            'path' => 'service_index',
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteService(
        Request $request,
        Service $service
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($service);
            $entityManager->flush();
        }
        return $this->redirectToRoute('service_index');
    }
}
