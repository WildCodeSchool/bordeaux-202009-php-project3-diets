<?php

namespace App\Controller;

use App\Entity\Picture;
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
use Symfony\Component\Validator\Constraints\NotNull;

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
                          PictureRepository $pictureRepository): Response
    {
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
            $service->setServiceIsValidated(false);
            $pictures = $formService->get('pictures')->getData();
            foreach ($pictures as $picture) {
                $picture->move(
                    $this->getParameter('uploadpicture_directory'),
                    $picture
                );
                $newPicture = new Picture();
                $newPicture->setUpdatedAt(new \DateTime('now'));
                $pictureName = substr($picture, -9);
                $newPicture->setName($pictureName);
                $service->addPicture($newPicture);
            }
            $entityManager->persist($service);
            $entityManager->flush();
            $this->redirectToRoute('service_index');
        }
        $service = $this->getDoctrine()
            ->getRepository(Service::class)
            ->findBy(['serviceIsValidated' => true], ['id' => 'desc']);

        $pictures = $this->getDoctrine()
            ->getRepository(Picture::class)
            ->findBy(['link' => null, 'event' => null]);

        return $this->render('service/index.html.twig', [
            'form_service' => $formService->createView(),
            'services' => $service,
            'form_search' => $formSearch->createView(),
            'services_search' => $services,
            'pictures' => $pictures,
            'path' => 'service_index',
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteService(
        Request $request,
        Service $service
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($service);
            $entityManager->flush();
        }
        return $this->redirectToRoute('service_index');
    }
}
