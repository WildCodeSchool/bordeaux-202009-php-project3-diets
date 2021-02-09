<?php

namespace App\DataFixtures;

use App\Entity\Expertise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExpertiseFixtures extends Fixture
{
    const EXPERTISES = [
        'Alimentation de l’enfant',
        'Rééquilibrage alimentaire',
        'Perte de poids',
        'Prise de poids',
        'Bien-être (retrouver un confort intestinal par exemple)',
        'Sportifs',
        'Régimes spécifiques (travail de nuit, végétarisme, sans gluten, ect…)',
        'Enfants et adolescents',
        'Femmes enceintes',
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::EXPERTISES as $expertiseData) {
            $expertise = new Expertise();
            $expertise->setName($expertiseData);
            $manager->persist($expertise);
            $this->addReference('expertise_' . $i, $expertise) ;
            $i++;
        }
        $manager->flush();
    }
}
