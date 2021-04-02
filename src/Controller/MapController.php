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
        $url = '/public/build/images/iconCompany.7fa76d36.png';

        return $this->render('map/index.html.twig', [
            'url' => $url,
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
            if (($company->getUser()->getLatitude() != null) && ($company->getUser()->getLongitude() != null)) {
                if (in_array('ROLE_COMPANY_SUBSCRIBER', $company->getUser()->getRoles(), $strict = true)) {
                    $datasCompany[] = ['Name' => $company->getName(), 'Lat' => $company->getUser()->getLatitude(), 'Long' => $company->getUser()->getLongitude(), 'id' => $company->getUser()->getId(), 'description' => $company->getDescription() ];
                }
            }
        }

        foreach ($freelancers as $freelancer) {
            if (($freelancer->getUser()->getLatitude() != null) && ($freelancer->getUser()->getLongitude() != null)) {
                if (in_array('ROLE_FREELANCER_SUBSCRIBER', $freelancer->getUser()->getRoles(), $strict = true)) {
                    $datasFreelancer[] = ['Name' => $freelancer->getName(), 'Lat' => $freelancer->getUser()->getLatitude(), 'Long' => $freelancer->getUser()->getLongitude(), 'id' => $freelancer->getUser()->getId(), 'description' => $freelancer->getDescription() ];
                }
            }
        }

        foreach ($dieteticians as $dietetician) {
            $dietSpecializations = $dietetician->getSpecializations();
            foreach ($dietSpecializations as $dietSpecialization) {
                if (($dietetician->getUser()->getLatitude() != null) && ($dietetician->getUser()->getLongitude() != null)) {
                        $datasDietetician[] = ['Name' => $dietetician->getLastname(), 'Lat' => $dietetician->getUser()->getLatitude(), 'Long' => $dietetician->getUser()->getLongitude(), 'id' => $dietetician->getUser()->getId(), 'Specialization' => $dietetician->getSpecializations()];
                }
            }
        }

        $datas = [$datasCompany, $datasFreelancer, $datasDietetician];

        return new JsonResponse($datas, 200);
    }
}
