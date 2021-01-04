<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PictureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');
        for ($i = 0; $i <= 100; $i++) {
            $picture = new Picture();
            $picture->setName($faker->domainWord);
            $picture->setLink("https://picsum.photos/id/" . $i . "/400/300");
            $manager->persist($picture);
            $this->addReference('picture_' . $i, $picture);
        }
        $manager->flush();
    }
}
