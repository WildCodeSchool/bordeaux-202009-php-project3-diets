<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i=1; $i<=50; $i++) {
            $user = new User();
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setBirthday($faker->dateTime);
            $user->setAdeli($faker->swiftBicNumber);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'MyPassword_' . $i));
            $user->setEmail($faker->email);
            $user->setAddress($faker->address);
            $user->setPhone($faker->numberBetween(1000000000, 5000000000));
            $user->setWebsite($faker->domainName);
            $user->addExpertise($this->getReference('expertise_' . rand(1,50)));
            if ($i % 2 === 1) {
                $user->addExpertise($this->getReference('expertise_' . rand(1,50)));
            };
            $user->setPicture($this->getReference('picture_' . rand(1,50)));
            $this->addReference('user_' . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
