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


        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->setCountry($faker->country);
        $user->addExpertise($this->getReference('expertise_' . rand(0, 8)));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'user'
        ));
        $user->setPicture($this->getReference('picture_8'));
        $this->addReference('user_0', $user);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('contributor@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->setCountry($faker->country);
        $user->addExpertise($this->getReference('expertise_' . rand(0, 8)));
        $user->setRoles(['ROLE_CONTRIBUTOR']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'contributor'
        ));
        $user->setPicture($this->getReference('picture_9'));
        $this->addReference('user_1', $user);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->setCountry($faker->country);
        $user->addExpertise($this->getReference('expertise_' . rand(0, 8)));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin'
        ));
        $user->setPicture($this->getReference('picture_10'));
        $this->addReference('user_2', $user);
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
