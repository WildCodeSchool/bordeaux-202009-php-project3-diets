<?php

namespace App\Tests\Entity;

use App\Entity\Freelancer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FreelancerTest extends KernelTestCase
{

    public function testEntityIsTrue()
    {
        $user = new User();

        $freelancer = new Freelancer();
        $freelancer->setName('Name');
        $freelancer->setUser($user);
        $freelancer->setDescription('Description');
        $freelancer->setSiret('12345678965412');

        $this->assertTrue($freelancer->getName() === 'Name');
        $this->assertTrue($freelancer->getUser() === $user);
        $this->assertTrue($freelancer->getDescription() === 'Description');
        $this->assertTrue($freelancer->getSiret() == '12345678965412');
    }

    public function testEntityIsFalse()
    {
        $user = new User();

        $freelancer = new Freelancer();
        $freelancer->setName('Name');
        $freelancer->setUser($user);
        $freelancer->setDescription('Description');
        $freelancer->setSiret('12345678965412');

        $this->assertFalse($freelancer->getName() === 'False');
        $this->assertFalse($freelancer->getUser() === new User());
        $this->assertFalse($freelancer->getDescription() === 'False');
        $this->assertFalse($freelancer->getSiret() == '12345678965448');
    }
}
