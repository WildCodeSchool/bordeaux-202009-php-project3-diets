<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\DieteticianRepository;
use App\Repository\FreelancerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/map", name="map")
     */
    public function index(): Response
    {
        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
        ]);
    }

    /**
     * @Route("/jsonMap", name="jsonMap")
     */
    public function jsonMap(
        CompanyRepository $companyRepository,
        FreelancerRepository $freelancerRepository,
        DieteticianRepository $dieteticianRepository
    ): JsonResponse {

        $datas = [];
        $datasCompany = [];
        $datasFreelancer = [];
        $datasDietetician = [];

        $companies = $companyRepository->findAll();
        $freelancers = $freelancerRepository->findAll();
        $dieteticians = $dieteticianRepository->findAll();


        foreach ($companies as $company) {
            $datasCompany[] = ['Name' => $company->getName(), 'Lat' => $company->getUser()->getLatitude(), 'Long' => $company->getUser()->getLongitude(), 'id' => $company->getUser()->getId(), 'description' => $company->getDescription() ];
        }

        foreach ($freelancers as $freelancer) {
            $datasFreelancer[] = ['Name' => $freelancer->getName(), 'Lat' => $freelancer->getUser()->getLatitude(), 'Long' => $freelancer->getUser()->getLongitude(), 'id' => $freelancer->getUser()->getId(), 'description' => $freelancer->getDescription() ];
        }

        foreach ($dieteticians as $dietetician) {
            $datasDietetician[] = ['Name' => $dietetician->getLastname(), 'Lat' => $dietetician->getUser()->getLatitude(), 'Long' => $dietetician->getUser()->getLongitude(), 'id' => $dietetician->getUser()->getId(), 'FirstName' => $dietetician->getFirstname() ];
        }

        $datas = [$datasCompany, $datasFreelancer, $datasDietetician];

        return new JsonResponse($datas, 200);
    }
}
