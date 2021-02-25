<?php


namespace App\DataFixtures;


use App\Entity\Dietetician;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class DieteticianFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $dietetician = new Dietetician();
        $dietetician->setFirstname($faker->firstName);
        $dietetician->setLastname($faker->lastName);
        $dietetician->setBirthday($faker->dateTime);
        $dietetician->setAdeli(351511535);
        $dietetician->setUser($this->getReference('user_1'));
        $manager->persist($dietetician);
        $manager->flush();



    }
    public function getDependencies()
    {
        return [UserFixtures::class];

    }


}