<?php

namespace App\Tests\Entity;

use App\Entity\Resource;
use App\Entity\ResourceFormat;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResourceTest extends KernelTestCase
{

    public function testEntityIsTrue()
    {
        $user = new User();
        $datetime = new \DateTime();
        $format = new ResourceFormat();

        $resource = new Resource();
        $resource->setUpdatedAt($datetime);
        $resource->setLink('https://www.google.fr');
        $resource->setPrice('100');
        $resource->setCreatedAt($datetime);
        $resource->setDescription('test d\'une nouvelle ressource');
        $resource->setName('new Resource');
        $resource->setUser($user);
        $resource->setResourceFormat($format);

        $this->assertTrue($resource->getName() === 'new Resource');
        $this->assertTrue($resource->getUpdatedAt() === $datetime);
        $this->assertTrue($resource->getLink() === 'https://www.google.fr');
        $this->assertTrue($resource->getPrice() == '100');
        $this->assertTrue($resource->getCreatedAt() === $datetime);
        $this->assertTrue($resource->getDescription() === 'test d\'une nouvelle ressource');
        $this->assertTrue($resource->getResourceFormat() === $format);
        $this->assertTrue($resource->getUser() === $user);
    }

    public function testEntityIsFalse()
    {
        $user = new User();
        $datetime = new \DateTime();
        $format = new ResourceFormat();

        $resource = new Resource();
        $resource->setUpdatedAt($datetime);
        $resource->setLink('https://www.google.fr');
        $resource->setPrice('100');
        $resource->setCreatedAt($datetime);
        $resource->setDescription('test d\'une nouvelle ressource');
        $resource->setName('new Resource');
        $resource->setUser($user);
        $resource->setResourceFormat($format);

        $this->assertFalse($resource->getName() === 'Resource');
        $this->assertFalse($resource->getUpdatedAt() === new \DateTime());
        $this->assertFalse($resource->getLink() === 'https://www.nouslesdiets.fr');
        $this->assertFalse($resource->getPrice() == '200');
        $this->assertFalse($resource->getCreatedAt() === new \DateTime());
        $this->assertFalse($resource->getDescription() === 'false');
        $this->assertFalse($resource->getResourceFormat() === new ResourceFormat());
        $this->assertFalse($resource->getUser() === new User());
    }
}
