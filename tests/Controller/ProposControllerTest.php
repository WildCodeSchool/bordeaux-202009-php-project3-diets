<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProposControllerTest extends WebTestCase
{
    public function testProposPageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/a-propos');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testProposPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/apropos');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
