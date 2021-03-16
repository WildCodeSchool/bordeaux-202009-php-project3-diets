<?php

namespace App\Tests\Entity;

use App\Entity\Service;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $user = new User();

        $service = new Service();
        $service->setName('Name');
        $service->setDescription('Description');
        $service->setLink('https://www.google.fr');
        $service->setPrice('100');
        $service->setServiceIsValidated(true);
        $service->setUser($user);

        $this->assertTrue($service->getName() === 'Name');
        $this->assertTrue($service->getDescription() === 'Description');
        $this->assertTrue($service->getLink() === 'https://www.google.fr');
        $this->assertTrue($service->getPrice() == '100');
        $this->assertTrue($service->getServiceIsValidated() === true);
        $this->assertTrue($service->getUser() === $user);
    }

    public function testEntityIsFalse()
    {
        $user = new User();

        $service = new Service();
        $service->setName('Name');
        $service->setDescription('Description');
        $service->setLink('https://www.google.fr');
        $service->setPrice('100');
        $service->setServiceIsValidated('true');
        $service->setUser($user);

        $this->assertFalse($service->getName() === 'False');
        $this->assertFalse($service->getDescription() === 'False');
        $this->assertFalse($service->getLink() === 'False');
        $this->assertFalse($service->getPrice() == '200');
        $this->assertFalse($service->getServiceIsValidated() === false);
        $this->assertFalse($service->getUser() === new User());
    }
}
