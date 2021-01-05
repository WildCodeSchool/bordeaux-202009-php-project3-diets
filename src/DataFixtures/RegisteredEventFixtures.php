<?php

namespace App\DataFixtures;

use App\Entity\RegisteredEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class RegisteredEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 50; $i++) {
            $registeredEvent = new RegisteredEvent();
            $registeredEvent->setIsOrganizer(rand(0, 1));
            $registeredEvent->setEvent(($this->getReference('event_' . rand(0, 49))));
            $registeredEvent->setUser(($this->getReference('user_' . rand(0, 49))));
            $manager->persist($registeredEvent);
            $this->addReference('registered_event_' . $i, $registeredEvent);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            EventFixtures::class,
        );
    }
}
