<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    const SERVICES = [
        [
            'name' => 'Elaboration d’un régime alimentaire',
            'description' => 'Voyez comment un diététiste collaborera avec vous pour établir un régime alimentaire répondant aux besoins de votre enfant et de votre famille.',
            'link' => 'https://www.aboutkidshealth.ca/fr/article?contentid=1742&language=french',
            'price' => '50',
        ],
        [
            'name' => 'Bilan diététique du sportif',
            'description' => 'Faire le point sur les habitudes alimentaires du sportif, cela veut dire : dépister des troubles du comportement alimentaire et évaluer les apports (énergie, macro, micronutriments). Ce bilan alimentaire peut être chiffré à partir d’un semainier* rempli par le sportif ou d’un rappel des 24 heures réalisé par le médecin ou le diététicien.',
            'link' => 'https://www.caducee.net/DossierSpecialises/nutrition/aprifel/bilan-dietetique-sportif.asp',
            'price' => '30',
        ],
    ];
    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::SERVICES as $serviceData) {
            $service = new Service();
            $service->setName($serviceData['name']);
            $service->setLink($serviceData['link']);
            $service->setPrice($serviceData['price']);
            $service->setDescription($serviceData['description']);
            $service->setUser($this->getReference('user_' . rand(0, 2)));
            $service->setServiceIsValidated(rand(0, 1));
            $service->setPicture($this->getReference('picture_' . rand(0, 50)));
            $manager->persist($service);
            $this->addReference('service_' . $i, $service);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(UserFixtures::class, PictureFixtures::class);
    }
}
