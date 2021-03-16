<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use App\Entity\Resource;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $datetime = new \DateTime();

        $contact = new Contact();
        $contact->setLastname('LastName');
        $contact->setFirstname('FirstName');
        $contact->setEmail('contact@gmail.com');
        $contact->setPhone('1258967485');
        $contact->setMessage('Message');
        $contact->setCreatedAt($datetime);

        $this->assertTrue($contact->getLastname() === 'LastName');
        $this->assertTrue($contact->getFirstname() === 'FirstName');
        $this->assertTrue($contact->getEmail() === 'contact@gmail.com');
        $this->assertTrue($contact->getPhone() == '1258967485');
        $this->assertTrue($contact->getMessage() === 'Message');
        $this->assertTrue($contact->getCreatedAt() === $datetime);
    }

    public function testEntityIsFalse()
    {
        $datetime = new \DateTime();

        $contact = new Contact();
        $contact->setLastname('LastName');
        $contact->setFirstname('FirstName');
        $contact->setEmail('contact@gmail.com');
        $contact->setPhone('1258967485');
        $contact->setMessage('Message');
        $contact->setCreatedAt($datetime);

        $this->assertFalse($contact->getLastname() === 'False');
        $this->assertFalse($contact->getFirstname() === 'False');
        $this->assertFalse($contact->getEmail() === 'contact2@gmail.com');
        $this->assertFalse($contact->getPhone() == '1258967447');
        $this->assertFalse($contact->getMessage() === 'False');
        $this->assertFalse($contact->getCreatedAt() === new \DateTime());
    }
}
