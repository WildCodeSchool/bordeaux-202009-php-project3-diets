<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EventControllerTest extends WebTestCase
{
    public function testEventPageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/evenement/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    public function testEventPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/event/');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

}
