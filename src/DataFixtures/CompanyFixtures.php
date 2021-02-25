<?php


namespace App\DataFixtures;


use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $company = new Company();
        $company->setName($faker->company);
        $company->setSiret(1234564645);
        $company->setDescription($faker->sentence);
        $company->setUser($this->getReference('user_0'));
        $manager->persist($company);

        $manager->flush();

    }

    public function getDependencies()
    {
        return [UserFixtures::class];

    }

}
