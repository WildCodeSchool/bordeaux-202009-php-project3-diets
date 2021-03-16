<?php

namespace App\Tests\Entity;

use App\Entity\SecuringPurchases;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SecuringPurchasesTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $user = new User();
        $datetime = new \DateTime();

        $securingPurchases = new SecuringPurchases();
        $securingPurchases->setUser($user);
        $securingPurchases->setExpirationAt($datetime);
        $securingPurchases->setIdentifier('Name');

        $this->assertTrue($securingPurchases->getUser() === $user);
        $this->assertTrue($securingPurchases->getExpirationAt() === $datetime);
        $this->assertTrue($securingPurchases->getIdentifier() === 'Name');
    }

    public function testEntityIsFalse()
    {
        $user = new User();
        $datetime = new \DateTime();

        $securingPurchases = new SecuringPurchases();
        $securingPurchases->setUser($user);
        $securingPurchases->setExpirationAt($datetime);
        $securingPurchases->setIdentifier('Name');

        $this->assertFalse($securingPurchases->getUser() === new User());
        $this->assertFalse($securingPurchases->getExpirationAt() === new \DateTime());
        $this->assertFalse($securingPurchases->getIdentifier() === 'False');
    }
}
