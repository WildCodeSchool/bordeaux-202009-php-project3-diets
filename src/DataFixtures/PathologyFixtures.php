<?php

namespace App\DataFixtures;

use App\Entity\Pathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PathologyFixtures extends Fixture implements DependentFixtureInterface
{
    const PATHOLOGIES = [
        'Administratif et législation',
        'Alimentation du nourrisson',
        'Alimentation de l’enfant',
        'Alimentation de l’adolescent',
        'Alimentation de la femme',
        'Alimentation de l’homme',
        'Alimentation de la femme enceinte',
        'Alimentation de la femme allaitante',
        'Alimentation du sportif',
        'Alimentation de la personne âgée',
        'Aliment',
        'Allergie',
        'Apports Nutritionnels Recommandés',
        'Cancer/chimiothérapie',
        'Chirurgie',
        'Complémentation orale',
        'Consultation diététique',
        'Diabète',
        'GERMCN',
        'Macronutriment',
        'Maigreur/Dénutrition/Malnutrition',
        'Maladie coeliaque',
        'Menu',
        'Micronutriment',
        'Nutrition entérale et parentérale',
        'Pathologie cardiovasculaire',
        'Pathologie digestive',
        'Pathologie ORL',
        'Pédiatrie',
        'Préparation culinaire',
        'Prise en charge patient',
        'Régime alimentaire',
        'Régime amaigrissant',
        'Régime thérapeutique',
        'Régime végétarien/végétalien',
        'Restauration collective',
        'Sels minéraux et oligo-éléments',
        'Surpoids et obésité',
        'Troubles du transit',
        'Vitamine',
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::PATHOLOGIES as $key => $pathologyName) {

            $pathology = new Pathology();
            $pathology->setName($pathologyName);
            $pathology->setIdentifier($pathology->getName());
            $pathology->addResource($this->getReference('resource_' . rand(1, 49)));
            $manager->persist($pathology);
            $this->addReference('pathology_' . $i, $pathology);

            $i++;
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [ResourceFixtures::class];
    }
}
