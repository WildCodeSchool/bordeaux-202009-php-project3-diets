<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testHomePageError()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
