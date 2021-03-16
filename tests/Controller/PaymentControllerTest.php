<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PaymentControllerTest extends WebTestCase
{
    public function testSubscribeValidPageRedirect()
    {
        $client = static::createClient();
        $client->request('GET', '/paiement/abonnement/valider');
        $this->assertResponseStatusCodeSame(Response::HTTP_MOVED_PERMANENTLY);
    }

    public function testSubscribeValidPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/paiement/abonnement/valid');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
