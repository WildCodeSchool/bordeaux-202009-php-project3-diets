<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TdProposControllerTest extends WebTestCase
{
    public function testAbout()
    {
        $client = static::createClient();
        $client->request('GET', '/a-propos');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH2About()
    {
        $client = static::createClient();
        $client->request('GET', '/a-propos');
        $this->assertSelectorTextContains('h2', 'A propos');
    }
}
