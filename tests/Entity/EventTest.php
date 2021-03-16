<?php

namespace App\Tests\Entity;

use _HumbugBox5f65e914a905\Nette\Utils\DateTime;
use App\Entity\Event;
use App\Entity\EventFormat;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EventTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $format = new EventFormat();
        $datetime = new \DateTime();

        $event = new Event();
        $event->setEventFormat($format);
        $event->setName('Name');
        $event->setDescription('Description');
        $event->setLink('https://www.google.fr');
        $event->setDateStart($datetime);
        $event->setDateEnd($datetime);
        $event->setPrice('100');
        $event->setCreatedAt($datetime);
        $event->setUpdatedAt($datetime);
        $event->setEventIsValidated(true);

        $this->assertTrue($event->getEventFormat() === $format);
        $this->assertTrue($event->getName() === 'Name');
        $this->assertTrue($event->getDescription() === 'Description');
        $this->assertTrue($event->getLink() === 'https://www.google.fr');
        $this->assertTrue($event->getDateStart() === $datetime);
        $this->assertTrue($event->getDateEnd() === $datetime);
        $this->assertTrue($event->getPrice() == '100');
        $this->assertTrue($event->getCreatedAt() === $datetime);
        $this->assertTrue($event->getUpdatedAt() === $datetime);
        $this->assertTrue($event->getEventIsValidated() === true);
    }

    public function testEntityIsFalse()
    {
        $format = new EventFormat();
        $datetime = new \DateTime();

        $event = new Event();
        $event->setEventFormat($format);
        $event->setName('Name');
        $event->setDescription('Description');
        $event->setLink('https://www.google.fr');
        $event->setDateStart($datetime);
        $event->setDateEnd($datetime);
        $event->setPrice('100');
        $event->setCreatedAt($datetime);
        $event->setUpdatedAt($datetime);
        $event->setEventIsValidated(true);

        $this->assertFalse($event->getEventFormat() === new EventFormat());
        $this->assertFalse($event->getName() === 'False');
        $this->assertFalse($event->getDescription() === 'False');
        $this->assertFalse($event->getLink() === 'https://www.false.fr');
        $this->assertFalse($event->getDateStart() === new \DateTime());
        $this->assertFalse($event->getDateEnd() === new \DateTime());
        $this->assertFalse($event->getPrice() == '200');
        $this->assertFalse($event->getCreatedAt() === new \DateTime());
        $this->assertFalse($event->getUpdatedAt() === new \DateTime());
        $this->assertFalse($event->getEventIsValidated() === false);
    }
}
