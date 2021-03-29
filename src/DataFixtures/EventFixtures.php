<?php

namespace App\DataFixtures;

use _HumbugBox5f65e914a905\Nette\Utils\DateTime;
use App\Entity\Event;
use App\Entity\RegisteredEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    const EVENTS = [
        [
            'name' => 'Traitement diététique de l’hypercholestérolémie de l’enfant',
            'description' => 'Le traitement diététique est la première étape de la prise en charge de toute hypercholestérolémie de l’enfant quel que soit son mécanisme et son importance. Dans cet article, les auteurs rappellent les bases nutritionnelles des recommandations diététiques et notamment les effets sur le LDL-cholestérol plasmatique des différentes familles d’acides gras alimentaires, des fibres et des phytostérols. Ils précisent les principes généraux et les objectifs du traitement diététique. Ils donnent des recommandations pratiques pour son application ainsi que des repères de consommation.',
            'link' => 'https://www.sciencedirect.com/science/article/abs/pii/S0929693X10001910',
            'date_start' => '2021-02-12 09:00:00',
            'date_end' => '2021-02-15 18:00:00',
            'price' => '250',
            'created_at' => '2021-02-02 15:00:00',
            'updated_at' => '2021-02-02 16:14:00',
        ],
        [
            'name' => 'Quel soin diététique dans le cadre de la chirurgie bariatrique ?',
            'description' => 'L’accompagnement du patient obèse en demande ou ayant eu une chirurgie de l’obésité est réalisé par une équipe pluriprofessionnelle dont les diététiciens-nutritionnistes. La conduite du soin diététique comme tout autre soin doit reposer, si cela est possible, sur une pratique fondée sur la preuve scientifique. Dans ce cadre, le Comité liaison alimentation nutrition (CLAN) central de l’Assistance publique–Hôpitaux de Paris a chargé un groupe de diététicien-nutritionniste accompagné par un référent médical de rédiger des recommandations de bonne pratique clinique portant sur les quatre grandes étapes de cette prise en charge : le temps de la décision opératoire, la préparation à l’intervention chirurgicale, le péri et le postopératoire immédiat et enfin le suivi à distance.',
            'link' => 'https://www.sciencedirect.com/science/article/abs/pii/S0007996011000575',
            'date_start' => '2021-02-19 09:30:00',
            'date_end' => '2021-02-21 18:30:00',
            'price' => '300',
            'created_at' => '2021-01-05 14:18:00',
            'updated_at' => '2021-01-05 19:00:00',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::EVENTS as $eventData) {
            $event = new Event();
            $event->setName($eventData['name']);
            $event->setDescription($eventData['description']);
            $event->setCreatedAt(new DateTime($eventData['created_at']));
            $event->setDateEnd(new DateTime($eventData['date_end']));
            $event->setDateStart(new DateTime($eventData['date_start']));
            $event->setLink($eventData['link']);
            $event->setEventIsValidated( true);
            $event->setPrice($eventData['price']);
            $event->setUpdatedAt(new DateTime($eventData['updated_at']));
            $event->setEventFormat($this->getReference('event_format_' . rand(1, 2)));
            $manager->persist($event);
            $this->addReference('event_' . $i, $event);
            $i++;
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            PictureFixtures::class,
            );
    }
}
