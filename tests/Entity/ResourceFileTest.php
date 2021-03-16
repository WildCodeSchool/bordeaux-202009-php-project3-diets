<?php

namespace App\Tests\Entity;

use App\Entity\Resource;
use App\Entity\ResourceFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResourceFileTest extends KernelTestCase
{
    public function testEntityIsTrue()
    {
        $resource = new Resource();
        $datetime = new \DateTime();

        $resourceFile = new ResourceFile();
        $resourceFile->setName('Name');
        $resourceFile->setLink('https://www.google.fr');
        $resourceFile->setUpdatedAt($datetime);
        $resourceFile->setResource($resource);

        $this->assertTrue($resourceFile->getName() === 'Name');
        $this->assertTrue($resourceFile->getLink() === 'https://www.google.fr');
        $this->assertTrue($resourceFile->getUpdatedAt() === $datetime);
        $this->assertTrue($resourceFile->getResource() === $resource);
    }

    public function testEntityIsFalse()
    {
        $resource = new Resource();
        $datetime = new \DateTime();

        $resourceFile = new ResourceFile();
        $resourceFile->setName('Name');
        $resourceFile->setLink('https://www.google.fr');
        $resourceFile->setUpdatedAt($datetime);
        $resourceFile->setResource($resource);

        $this->assertFalse($resourceFile->getName() === 'False');
        $this->assertFalse($resourceFile->getLink() === 'False');
        $this->assertFalse($resourceFile->getUpdatedAt() === new \DateTime());
        $this->assertFalse($resourceFile->getResource() === new Resource());
    }
}
