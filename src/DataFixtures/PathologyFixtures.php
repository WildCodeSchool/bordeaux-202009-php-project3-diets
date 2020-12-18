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
        $pathology = new Pathology();
        $pathology->setName($faker->word);
        $pathology->setIdentifier($pathology->getName());
        $manager->persist($pathology);
        $pathology = new Pathology();
        $pathology->setName($faker->word);
        $pathology->setIdentifier($pathology->getName());
        $manager->persist($pathology);
        $pathology = new Pathology();
        $pathology->setName($faker->word);
        $pathology->setIdentifier($pathology->getName());
        $manager->persist($pathology);
        $manager->flush();
    }
}
