<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistrationPageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegistrationPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/registration');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
