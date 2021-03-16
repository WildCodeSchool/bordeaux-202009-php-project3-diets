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
