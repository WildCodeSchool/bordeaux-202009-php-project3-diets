<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EventFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $event = new Event();
            $event->setName($faker->name);
            $event->setDescription($faker->text);
            $event->setCreatedAt($faker->dateTime);
            $event->setDateEnd($faker->dateTime);
            $event->setDateStart($faker->dateTime);
            $event->setLink($faker->url);
            $event->setPicture($this->getReference('picture_' . $i));
            $event->setEventIsValidated(rand(0, 1));
            $event->setPrice($faker->numberBetween(0, 1000));
            $event->setUpdatedAt($faker->dateTime);
            $event->setEventFormat($this->getReference('event_format_' . rand(0, 49)));
            $event->setRegisteredEvent($this->getReference('registered_event_' . rand(0, 49)));
            $manager->persist($event);
            $this->addReference('event_' . $i, $event);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [PictureFixtures::class];
    }
}
