<?php


namespace App\Service\Format;


use App\Repository\EventRepository;
use App\Repository\ResourceRepository;
use App\Repository\UserRepository;

class VerifyUseFormat
{
    private $resourceRepository;

    private $userRepository;

    private $eventRepository;

    public function __construct(
        ResourceRepository $resourceRepository,
        UserRepository $userRepository,
        EventRepository $eventRepository
    ) {
        $this->resourceRepository = $resourceRepository;
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
    }

    public function usePathology(string $pathology): int
    {
        $search = $this->resourceRepository->findBy(['pathology' => $pathology]);
        dd($search);
    }
}