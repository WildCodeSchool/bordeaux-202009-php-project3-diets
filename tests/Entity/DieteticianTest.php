<?php

namespace App\Tests\Entity;

use App\Entity\Dietetician;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DieteticianTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $user = new User();
        $datetime = new \DateTime();

        $dietetician = new Dietetician();
        $dietetician->setLastname('LastName');
        $dietetician->setFirstname('FirstName');
        $dietetician->setUser($user);
        $dietetician->setBirthday($datetime);
        $dietetician->setAdeli('259674894');

        $this->assertTrue($dietetician->getLastname() === 'LastName');
        $this->assertTrue($dietetician->getFirstname() === 'FirstName');
        $this->assertTrue($dietetician->getUser() === $user);
        $this->assertTrue($dietetician->getBirthday() == $datetime);
        $this->assertTrue($dietetician->getAdeli() == '259674894');
    }

    public function testEntityIsFalse()
    {
        $user = new User();
        $datetime = new \DateTime();

        $dietetician = new Dietetician();
        $dietetician->setLastname('LastName');
        $dietetician->setFirstname('FirstName');
        $dietetician->setUser($user);
        $dietetician->setBirthday($datetime);
        $dietetician->setAdeli('259674894');

        $this->assertFalse($dietetician->getLastname() === 'False');
        $this->assertFalse($dietetician->getFirstname() === 'False');
        $this->assertFalse($dietetician->getUser() === new User());
        $this->assertFalse($dietetician->getBirthday() === new \DateTime());
        $this->assertFalse($dietetician->getAdeli() == '259674887');
    }
}
