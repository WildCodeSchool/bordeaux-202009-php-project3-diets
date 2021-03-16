<?php

namespace App\Tests\Controller;

use App\Entity\ResourceFormat;
use App\Repository\UserRepository;
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

    public function testResourceForm()
    {
        $format = new ResourceFormat();
        $format->setFormat('1');

        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/connaissances/');

        $client->submitForm('Valider', [
            'resource[name]' => 'Name',
            'resource[link]' => 'https://www.google.fr',
            'resource[price]' => '150',
            'resource[description]' => 'Description',
            'resource[resourceFormat]' => $format->getFormat(),
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
