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
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $resourceFormat = new ResourceFormat();
            $resourceFormat->setFormat($faker->word);
            $resourceFormat->setIdentifier($resourceFormat->getFormat());
            $manager->persist($resourceFormat);
            $this->addReference('resource_format_' . $i, $resourceFormat);
        }
        $manager->flush();
    }
}
