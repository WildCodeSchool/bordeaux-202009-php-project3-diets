<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BasketControllerTest extends WebTestCase
{
    public function testBasketPageFound()
    {
        $client = static::createClient();
        $client->request('GET', '/panier/');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testBasketPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/basket/');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }




}
