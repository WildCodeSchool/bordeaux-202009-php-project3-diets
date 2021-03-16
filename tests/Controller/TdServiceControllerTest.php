<?php

namespace App\Tests\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TdServiceControllerTest extends WebTestCase
{
    public function testServicePage()
    {
        $client = static::createClient();
        $client->request('GET', '/service/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
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
