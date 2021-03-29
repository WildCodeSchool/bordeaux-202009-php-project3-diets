<?php


namespace App\DataFixtures;


use App\Entity\Specialization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpecializationFixtures extends Fixture
{
    const SPECIALIZATIONS = [
        'TCA',
        'FODMAP',
        'Langue des Signes Françaises (LSF)',
        'Braille',
    ];

    public function load(ObjectManager $objectManager)
    {
        foreach (self::SPECIALIZATIONS as $specializationData) {
            $specialization = new Specialization();
            $specialization->setName($specializationData);
            $objectManager->persist($specialization);
        }
        $objectManager->flush();

    }

}
