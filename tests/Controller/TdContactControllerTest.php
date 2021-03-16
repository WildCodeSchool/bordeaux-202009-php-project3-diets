<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TdContactControllerTest extends WebTestCase
{
    public function testContact()
    {
        $client = static::createClient();
        $client->request('GET', '/contact/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testContactForm()
    {
        $client = static::createClient();
        $client->request('GET', '/contact/');
        $client->submitForm('Envoyer le message', [
            'contact[lastname]' => 'Doe',
            'contact[firstname]' => 'John',
            'contact[email]' => 'john@doe.fr',
            'contact[phone]' => '0685749685',
            'contact[message]' => 'Test d\envoi d\'un message',
        ]);
        $this->assertSelectorExists('.alert.alert-success');
    }
}
