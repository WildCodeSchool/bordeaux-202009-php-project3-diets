<?php

namespace App\DataFixtures;

use App\Entity\EventFormat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EventFormatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $eventFormat = new EventFormat();
        $eventFormat->setFormat('formation ponctuelle');
        $manager->persist($eventFormat);
        $this->addReference('event_format_1', $eventFormat);

        $eventFormat = new EventFormat();
        $eventFormat->setFormat('formation annuelle');
        $manager->persist($eventFormat);
        $this->addReference('event_format_2', $eventFormat);

        $manager->flush();
    }
}
