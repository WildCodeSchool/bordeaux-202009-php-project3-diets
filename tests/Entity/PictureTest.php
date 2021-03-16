<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Picture;
use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PictureTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $service = new Service();
        $event = new Event();
        $datetime = new \DateTime();

        $picture = new Picture();
        $picture->setName('Name');
        $picture->setLink('https://www.google.fr');
        $picture->setUpdatedAt($datetime);
        $picture->setService($service);
        $picture->setEvent($event);

        $this->assertTrue($picture->getName() === 'Name');
        $this->assertTrue($picture->getLink() === 'https://www.google.fr');
        $this->assertTrue($picture->getUpdatedAt() === $datetime);
        $this->assertTrue($picture->getService() === $service);
        $this->assertTrue($picture->getEvent() === $event);
    }

    public function testEntityIsFalse()
    {
        $service = new Service();
        $event = new Event();
        $datetime = new \DateTime();

        $picture = new Picture();
        $picture->setName('Name');
        $picture->setLink('https://www.google.fr');
        $picture->setUpdatedAt($datetime);
        $picture->setService($service);
        $picture->setEvent($event);

        $this->assertFalse($picture->getName() === 'False');
        $this->assertFalse($picture->getLink() === 'False');
        $this->assertFalse($picture->getUpdatedAt() === new \DateTime());
        $this->assertFalse($picture->getService() === new Service());
        $this->assertFalse($picture->getEvent() === new Event());
    }
}
