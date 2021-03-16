<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ResourceControllerTest extends WebTestCase
{
    public function testResourcePageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/ressource/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testResourcePageError()
    {
        $client = static::createClient();
        $client->request('GET', '/resources/');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function h1ResourcePage()
    {
        $client = static::createClient();
        $client->request('GET', '/ressource/');
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Nous les Diets !!!');
    }
}

