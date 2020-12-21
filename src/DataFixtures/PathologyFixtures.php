<?php

namespace App\DataFixtures;

use App\Entity\Pathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PathologyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i < 10; $i++) {
            $pathology = new Pathology();
            $pathology->setName($faker->word);
            $pathology->setIdentifier($pathology->getName());
            $manager->persist($pathology);
            $this->addReference('pathology_' . $i, $pathology);
        }
        $manager->flush();
    }
}
