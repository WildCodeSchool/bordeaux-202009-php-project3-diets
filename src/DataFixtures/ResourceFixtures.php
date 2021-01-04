<?php

namespace App\DataFixtures;

use App\Entity\Resource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i < 50; $i++) {
            $resource = new Resource();
            $resource->setUpdatedAt($faker->dateTimeAD);
            $resource->setLink($faker->url);
            $resource->setCreatedAt($faker->dateTimeAD);
            $resource->setDescription($faker->text);
            $resource->setName($faker->word);
            $resource->setResourceFormat($this->getReference('resource_format_' . rand(0, 49)));
            $resource->setUser($this->getReference('user_' . rand(0, 49)));
            $manager->persist($resource);
            $this->addReference('resource_' . $i, $resource);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(ResourceFormatFixtures::class,
        UserFixtures::class,);
    }
}
