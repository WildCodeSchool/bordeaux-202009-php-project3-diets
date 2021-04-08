<?php

namespace App\Service\Publicity;

use App\Repository\UserRepository;

class PublicityService
{
    protected $userRepository;
    protected const COUNTNUMBER = 1;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function addPublicity()
    {
        $companiespublicity = [];
        $freelancersPublicity = [];

        $companiesSubscriber = $this->userRepository->findByRole("ROLE_COMPANY_SUBSCRIBER");
        if (!empty($companiesSubscriber)) {
            $rnd1Company = rand(0, count($companiesSubscriber) - 1);
            $companiespublicity[0] = $companiesSubscriber[$rnd1Company];
            if (count($companiesSubscriber) !== self::COUNTNUMBER) {
                do {
                    $rnd2Company = rand(0, count($companiesSubscriber) - 1);
                } while ($rnd1Company == $rnd2Company);
                $companiespublicity[1] = $companiesSubscriber[$rnd2Company];
            }
        }

        $freelancersSubscriber = $this->userRepository->findByRole("ROLE_FREELANCER_SUBSCRIBER");
        if (!empty($freelancersSubscriber)) {
            $rnd1Freelancer = rand(0, count($freelancersSubscriber) - 1);
            $freelancersPublicity[0] = $freelancersSubscriber[$rnd1Freelancer];

            if (count($freelancersSubscriber) !== self::COUNTNUMBER) {
                do {
                    $rnd2Freelancer = rand(0, count($freelancersSubscriber) - 1);
                } while ($rnd1Freelancer == $rnd2Freelancer);
                $freelancersPublicity[1] = $freelancersSubscriber[$rnd2Freelancer];
            }
        }

        return array($companiespublicity, $freelancersPublicity);
    }
}
