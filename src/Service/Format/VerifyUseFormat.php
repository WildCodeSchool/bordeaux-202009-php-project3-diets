<?php


namespace App\Service\Format;


use App\Entity\Pathology;
use App\Repository\EventRepository;
use App\Repository\PathologyRepository;
use App\Repository\ResourceRepository;
use App\Repository\UserRepository;

class VerifyUseFormat
{
    private $resourceRepository;

    private $userRepository;

    private $eventRepository;

    private $pathologyRepository;

    public function __construct(
        ResourceRepository $resourceRepository,
        UserRepository $userRepository,
        EventRepository $eventRepository,
        PathologyRepository $pathologyRepository
    ) {
        $this->resourceRepository = $resourceRepository;
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
        $this->pathologyRepository = $pathologyRepository;
    }

}