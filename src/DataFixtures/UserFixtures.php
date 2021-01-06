<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i <= 49; $i++) {
            $user = new User();
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setBirthday($faker->dateTime);
            $user->setAdeli($faker->swiftBicNumber);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'MyPassword_' . $i));
            $user->setEmail($faker->email);
            $user->setRoles(['ROLE_USER']);
            $user->setAddress($faker->address);
            $user->setPhone($faker->numberBetween(100000, 500000));
            $user->setWebsite($faker->domainName);
            $user->addExpertise($this->getReference('expertise_' . rand(0, 49)));
            if ($i % 2 === 1) {
                $user->addExpertise($this->getReference('expertise_' . rand(0, 49)));
            };
            $user->setPicture($this->getReference('picture_' . $i));
            $this->addReference('user_' . $i, $user);
            $manager->persist($user);
        }

        $user = new User();
        $user->setLastname($faker->lastName);
        $user->setFirstname($faker->firstName);
        $user->setBirthday($faker->dateTime);
        $user->setAdeli($faker->swiftBicNumber);
        $user->setEmail('contributor@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->addExpertise($this->getReference('expertise_' . rand(0, 49)));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'contributorpassword'
        ));
        $user->setPicture($this->getReference('picture_50'));
        $this->addReference('user_50', $user);
        $manager->persist($user);

        $user = new User();
        $user->setLastname($faker->lastName);
        $user->setFirstname($faker->firstName);
        $user->setBirthday($faker->dateTime);
        $user->setAdeli($faker->swiftBicNumber);
        $user->setEmail('bonjour@bonjour.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->addExpertise($this->getReference('expertise_' . rand(0, 49)));
        $user->setRoles(['ROLE_CONTRIBUTOR']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'bonjour'
        ));
        $user->setPicture($this->getReference('picture_51'));
        $this->addReference('user_51', $user);
        $manager->persist($user);

        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            PictureFixtures::class,
            ExpertiseFixtures::class,
        );
    }
}
