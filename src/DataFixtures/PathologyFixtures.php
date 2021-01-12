<?php

namespace App\DataFixtures;

use App\Entity\Pathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PathologyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i < 10; $i++) {
            $pathology = new Pathology();
            $pathology->setName($faker->word);
            $pathology->setIdentifier($pathology->getName());
            $pathology->addResource($this->getReference('resource_' . rand(1, 49)));
            $manager->persist($pathology);
            $this->addReference('pathology_' . $i, $pathology);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [ResourceFixtures::class];
    }
}
