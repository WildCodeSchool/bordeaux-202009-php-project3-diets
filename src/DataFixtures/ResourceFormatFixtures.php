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
        $resourceFormat->setBackground("https://i.pinimg.com/564x/d4/e9/71/d4e97113fc4b61ab7c805321909c02d2.jpg");
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_1', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('image');
        $resourceFormat->setIcon("fas fa-5x fa-image");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $resourceFormat->setBackground("https://images.assetsdelivery.com/compings_v2/natis76/natis761611/natis76161100186.jpg");
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_2', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('pdf');
        $resourceFormat->setIcon("fas fa-5x fa-file-pdf");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $resourceFormat->setBackground("https://images.assetsdelivery.com/compings_v2/jahmaica/jahmaica1609/jahmaica160900060.jpg");
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_3', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('site web');
        $resourceFormat->setIcon("fas fa-5x fa-globe");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $resourceFormat->setBackground("https://images.assetsdelivery.com/compings_v2/photoallel/photoallel1902/photoallel190200090.jpg");
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_4', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('audio');
        $resourceFormat->setIcon("fas fa-5x fas fa-volume-up");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $resourceFormat->setBackground("https://images.assetsdelivery.com/compings_v2/nipapornnan/nipapornnan1506/nipapornnan150600028.jpg");
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_5', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('autre');
        $resourceFormat->setIcon("fas fa-5x fas fa-circle");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $resourceFormat->setBackground("https://images.assetsdelivery.com/compings_v2/belchonock/belchonock1906/belchonock190609787.jpg");
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_6', $resourceFormat);

        $resourceFormat = new ResourceFormat();
        $resourceFormat->setFormat('visioconference');
        $resourceFormat->setIcon("fas fa-5x fas fa-play-circle");
        $resourceFormat->setIdentifier($resourceFormat->getFormat());
        $resourceFormat->setBackground("https://i.pinimg.com/564x/d4/e9/71/d4e97113fc4b61ab7c805321909c02d2.jpg");
        $manager->persist($resourceFormat);
        $this->addReference('resource_format_7', $resourceFormat);



        $manager->flush();
    }
}
