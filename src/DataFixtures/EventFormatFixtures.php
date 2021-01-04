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
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $eventFormat = new EventFormat();
            $eventFormat->setFormat($faker->word);
            $eventFormat->setIdentifier($eventFormat->getFormat());
            $manager->persist($eventFormat);
            $this->addReference('event_format_' . $i, $eventFormat);
        }
        $manager->flush();
    }
}
