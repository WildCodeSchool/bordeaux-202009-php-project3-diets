<?php

namespace App\Tests\Entity;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompanyTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $company = new Company();
        $user = new User();

        $company->setUser($user);
        $company->setName('Name');
        $company->setDescription('Description');
        $company->setSiret('12547895412589');

        $this->assertTrue($company->getUser() === $user);
        $this->assertTrue($company->getName() === 'Name');
        $this->assertTrue($company->getDescription() === 'Description');
        $this->assertTrue($company->getSiret() == '12547895412589');
    }

    public function testEntityIsFalse()
    {
        $company = new Company();
        $user = new User();

        $company->setUser($user);
        $company->setName('Name');
        $company->setDescription('Description');
        $company->setSiret('12547895412589');

        $this->assertFalse($company->getUser() === new User());
        $this->assertFalse($company->getName() === 'False');
        $this->assertFalse($company->getDescription() === 'False');
        $this->assertFalse($company->getSiret() == '12547895412412');
    }
}
