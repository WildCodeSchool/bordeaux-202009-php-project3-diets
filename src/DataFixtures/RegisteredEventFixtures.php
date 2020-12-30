<?php

namespace App\DataFixtures;

use App\Entity\RegisteredEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;
use Faker;

class RegisteredEventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 50; $i++) {
            $registeredEvent = new RegisteredEvent();
            $registeredEvent->setIsOrganizer(rand(0, 1));
            $manager->persist($registeredEvent);
            $this->addReference('registered_event_' . $i, $registeredEvent);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
