<?php


namespace App\Service\MultiUpload;

use App\Entity\Picture;
use App\Entity\ResourceFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MultiUploadService extends AbstractController
{

    public function createMultiUploadToResource($formResource, $newResource)
    {
        $files = $formResource->get('resourceFiles')->getData();
        foreach ($files as $file) {
            $file->move(
                $this->getParameter('uploadresource_directory'),
                $file
            );
            $newResourceFile = new ResourceFile();
            $newResourceFile->setUpdatedAt(new \DateTime('now'));
            $resourceName = substr($file, -9);
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
                $this->getParameter('uploadpicture_directory'),
                $picture
            );
            $newPicture = new Picture();
            $newPicture->setUpdatedAt(new \DateTime('now'));
            $pictureName = substr($picture, -9);
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
                $this->getParameter('uploadpicture_directory'),
                $picture
            );
            $newPicture = new Picture();
            $newPicture->setUpdatedAt(new \DateTime('now'));
            $pictureName = substr($picture, -9);
            $newPicture->setName($pictureName);
            $event->addPicture($newPicture);
        }
        return $event;
    }

}