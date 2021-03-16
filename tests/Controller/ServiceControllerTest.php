<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ServiceControllerTest extends WebTestCase
{
    public function testServicePageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/service/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegistrationPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/services/');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
