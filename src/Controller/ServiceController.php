<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Service;
use App\Form\SearchResourceType;
use App\Form\ServiceType;
use App\Repository\CompanyRepository;
use App\Repository\FreelancerRepository;
use App\Repository\PictureRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Service\MultiUpload\MultiUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        ServiceRepository $serviceRepository,
        MultiUploadService $multiUploadService,
        UserRepository $userRepository
    ): Response {
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
            $service = $multiUploadService->createMultiUploadToService($formService, $service);
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

        $company = '';
        $freelancer = '';

        $companies = $userRepository->findByRole("ROLE_COMPANY_SUBSCRIBER");
        if (!empty($companies)){
            $randcompany = rand(1, count($companies));
            $company = $companies[$randcompany-1];
        }

        $freelancers = $userRepository->findByRole("ROLE_FREELANCER_SUBSCRIBER");
        if (!empty($freelancers)){
            $randfreelancer = rand(1, count($freelancers));
            $freelancer = $freelancers[$randfreelancer-1];
        }

        return $this->render('service/index.html.twig', [
            'form_service' => $formService->createView(),
            'services' => $service,
            'form_search' => $formSearch->createView(),
            'services_search' => $services,
            'pictures' => $pictures,
            'path' => 'service_index',
            'company' => $company,
            'freelancer' => $freelancer,
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="delete", methods={"DELETE"})
     *
     */
    public function deleteService(
        Request $request,
        Service $service
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $pictures = $service->getPictures();
            foreach ($pictures as $picture) {
                $entityManager->remove($picture);
                $entityManager->flush();
            }
            $entityManager->remove($service);
            $entityManager->flush();
        }
        return $this->redirectToRoute('service_index');
    }

    /**
     * @Route("/picture/{id}", name="delete_picture", methods={"DELETE"})
     *
     */
    public function deletePicture(
        Request $request,
        Picture $picture
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($picture);
            $entityManager->flush();
        }
        return $this->redirectToRoute('service_index');
    }
}
