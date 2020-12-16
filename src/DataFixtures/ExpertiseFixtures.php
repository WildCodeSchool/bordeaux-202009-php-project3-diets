<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Expertise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ExpertiseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $expertise = new Expertise();
            $expertise->setName($faker->domainWord);
            $manager->persist($expertise);
            $this->addReference('expertise_' . ($i + 1), $expertise) ;
        }
        $manager->flush();
    }
}
