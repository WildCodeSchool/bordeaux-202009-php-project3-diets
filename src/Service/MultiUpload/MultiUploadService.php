<?php

namespace App\Service\MultiUpload;

use App\Entity\Picture;
use App\Entity\ResourceFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MultiUploadService
{
    private const VALUEOFSUBSTRONNAME = -9;

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function createMultiUploadToResource($formResource, $newResource)
    {
        $files = $formResource->get('resourceFiles')->getData();
        foreach ($files as $file) {
            $file->move(
                $this->params->get('uploadresource_directory'),
                $file
            );
            $newResourceFile = new ResourceFile();
            $newResourceFile->setUpdatedAt(new \DateTime('now'));
            $resourceName = substr($file, self::VALUEOFSUBSTRONNAME);
            $newResourceFile->setName($resourceName);
            $newResource->addResourceFile($newResourceFile);
        }
        return $newResource;
    }

    public function createMultiUploadToService($formService, $service)
    {
        $pictures = $formService->get('pictures')->getData();
        foreach ($pictures as $picture) {
            $picture->move(
                $this->params->get('uploadpicture_directory'),
                $picture
            );
            $newPicture = new Picture();
            $newPicture->setUpdatedAt(new \DateTime('now'));
            $pictureName = substr($picture, self::VALUEOFSUBSTRONNAME);
            $newPicture->setName($pictureName);
            $service->addPicture($newPicture);
        }
        return $service;
    }

    public function createMultiUploadToEvent($formEvent, $event)
    {
        $pictures = $formEvent->get('pictures')->getData();
        foreach ($pictures as $picture) {
            $picture->move(
                $this->params->get('uploadpicture_directory'),
                $picture
            );
            $newPicture = new Picture();
            $newPicture->setUpdatedAt(new \DateTime('now'));
            $pictureName = substr($picture, self::VALUEOFSUBSTRONNAME);
            $newPicture->setName($pictureName);
            $event->addPicture($newPicture);
        }
        return $event;
    }
}
