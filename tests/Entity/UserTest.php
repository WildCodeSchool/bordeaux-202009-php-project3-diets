<?php

namespace App\Tests\Entity;

use App\Entity\Picture;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $picture = new Picture();

        $user = new User();
        $user->setEmail('john@doe.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('000000');
        $user->setAddress('Adresse');
        $user->setPhone('0125364785');
        $user->setWebsite('https://www.google.fr');
        $user->setIsVerified(true);
        $user->setCountry('France');
        $user->setPicture($picture);

        $this->assertTrue($user->getEmail() === 'john@doe.fr');
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
        $this->assertTrue($user->getPassword() === '000000');
        $this->assertTrue($user->getAddress() === 'Adresse');
        $this->assertTrue($user->getPhone() == '0125364785');
        $this->assertTrue($user->getWebsite() === 'https://www.google.fr');
        $this->assertTrue($user->isVerified() === true);
        $this->assertTrue($user->getCountry() === 'France');
        $this->assertTrue($user->getPicture() === $picture);
    }

    public function testEntityIsFalse()
    {
        $picture = new Picture();

        $user = new User();
        $user->setEmail('john@doe.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('000000');
        $user->setAddress('Adresse');
        $user->setPhone('0125364785');
        $user->setWebsite('https://www.google.fr');
        $user->setIsVerified(true);
        $user->setCountry('France');
        $user->setPicture($picture);

        $this->assertFalse($user->getEmail() === 'False');
        $this->assertFalse($user->getRoles() === ['ROLE_CONTRIBUTOR']);
        $this->assertFalse($user->getPassword() === '256847');
        $this->assertFalse($user->getAddress() === 'False');
        $this->assertFalse($user->getPhone() == '0125364714');
        $this->assertFalse($user->getWebsite() === 'False');
        $this->assertFalse($user->isVerified() === false);
        $this->assertFalse($user->getCountry() === 'False');
        $this->assertFalse($user->getPicture() === new Picture());
    }
}
