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
        UserRepository $userRepository,
        CompanyRepository $companyRepository,
        FreelancerRepository $freelancerRepository,
        DieteticianRepository $dieteticianRepository
    ): JsonResponse {

        $datas = [];
        $users = $userRepository->findAll();

        foreach ($users as $user) {

            $datas[] = ['Name' => $user->getUsername(), 'Lat' => $user->getLatitude(), 'Long' => $user->getLongitude(), 'id' => $user->getId(), 'divers' => $user->getAddress()];
        }

        return new JsonResponse($datas, 200);
    }
}
