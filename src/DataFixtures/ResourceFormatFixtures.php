<?php

namespace App\DataFixtures;

use App\Entity\ResourceFormat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ResourceFormatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('video');
        $resourceFormat->setIcon("fas fa-5x fa-video");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_1', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('image');
        $resourceFormat->setIcon("fas fa-5x fa-image");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_2', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('pdf');
        $resourceFormat->setIcon("fas fa-5x fa-file-pdf");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_3', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('site web');
        $resourceFormat->setIcon("fas fa-5x fa-globe");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_4', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('audio');
        $resourceFormat->setIcon("fas fa-5x fas fa-volume-up");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_5', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('autre');
        $resourceFormat->setIcon("fas fa-5x fas fa-circle");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_6', $resourceFormat);



        $manager->flush();
    }
}
