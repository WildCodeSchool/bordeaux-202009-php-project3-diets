<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest extends WebTestCase
{
    public function testContactPageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/contact/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    public function testContactPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/contacter/');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

}
