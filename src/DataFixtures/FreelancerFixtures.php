<?php


namespace App\DataFixtures;

use App\Entity\Freelancer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FreelancerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $freelancer = new Freelancer();
        $freelancer->setName($faker->company);
        $freelancer->setSiret(543435135);
        $freelancer->setDescription($faker->sentence);
        $freelancer->setUser($this->getReference('user_3'));
        $manager->persist($freelancer);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }

}
