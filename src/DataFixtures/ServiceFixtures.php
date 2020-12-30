<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_Fr');
        for ($i = 0; $i < 50; $i++) {
            $service = new Service();
            $service->setName($faker->word);
            $service->setLink($faker->url);
            $service->setPrice($faker->numberBetween(0, 1000));
            $service->setDescription($faker->text(200));
            $service->setUser($this->getReference('user_' . rand(0, 49)));
            $service->setServiceIsValidated(rand(0, 1));
            $manager->persist($service);
            $this->addReference('service_' . $i, $service);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
