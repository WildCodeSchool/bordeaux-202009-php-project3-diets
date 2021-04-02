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
        $user->setEmail('company@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->setCountry($faker->country);
        $user->setRoles(['ROLE_COMPANY']);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'company'
        ));
        $user->setPicture($this->getReference('picture_7'));
        $this->addReference('user_0', $user);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('dietetician@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->setCountry($faker->country);
        $user->setRoles(['ROLE_DIETETICIAN']);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'dietetician'
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
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin'
        ));
        $user->setPicture($this->getReference('picture_10'));
        $this->addReference('user_2', $user);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('freelancer@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->setCountry($faker->country);
        $user->setRoles(['ROLE_FREELANCER']);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'freelancer'
        ));
        $user->setPicture($this->getReference('picture_8'));
        $this->addReference('user_3', $user);
        $manager->persist($user);

        $manager->flush();

        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setAddress($faker->address);
        $user->setPhone($faker->numberBetween(100000, 500000));
        $user->setWebsite($faker->domainName);
        $user->setCountry($faker->country);
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'user'
        ));
        $user->setPicture($this->getReference('picture_11'));
        $this->addReference('user_4', $user);
        $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PictureFixtures::class,
        );
    }
}
