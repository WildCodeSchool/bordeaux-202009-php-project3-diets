<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ServiceControllerTest extends WebTestCase
{
    public function testServicePageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/service/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegistrationPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/services/');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testH2ServicePage()
    {
        $client = static::createClient();
        $client->request('GET', '/service/');
        $this->assertSelectorTextContains('h2', 'Service');
    }

    public function testServiceForm()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/service/');

        $client->submitForm('Valider', [
            'service[name]' => 'Name',
            'service[link]' => 'https://www.google.fr',
            'service[price]' => '150',
            'service[description]' => 'Description',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
