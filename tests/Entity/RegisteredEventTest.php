<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\RegisteredEvent;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RegisteredEventTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $event = new Event();
        $user = new User();

        $registeredEvent = new RegisteredEvent();
        $registeredEvent->setEvent($event);
        $registeredEvent->setUser($user);
        $registeredEvent->setIsOrganizer(true);

        $this->assertTrue($registeredEvent->getEvent() === $event);
        $this->assertTrue($registeredEvent->getUser() === $user);
        $this->assertTrue($registeredEvent->getIsOrganizer() === true);
    }

    public function testEntityIsFalse()
    {
        $event = new Event();
        $user = new User();

        $registeredEvent = new RegisteredEvent();
        $registeredEvent->setEvent($event);
        $registeredEvent->setUser($user);
        $registeredEvent->setIsOrganizer(true);

        $this->assertFalse($registeredEvent->getEvent() === new Event());
        $this->assertFalse($registeredEvent->getUser() === new User());
        $this->assertFalse($registeredEvent->getIsOrganizer() === false);
    }
}
