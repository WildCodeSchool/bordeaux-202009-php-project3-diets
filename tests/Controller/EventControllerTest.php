<?php

namespace App\Tests\Controller;

use App\Entity\EventFormat;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EventControllerTest extends WebTestCase
{
    public function testEventPageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/evenement/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEventPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/event/');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testEventForm()
    {
        $format = new EventFormat();
        $format->setFormat('1');

        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/evenement/');

        $client->submitForm('Valider', [
            'event[name]' => 'Name',
            'event[eventFormat]' => $format->getFormat(),
            'event[price]' => '150',
            'event[link]' => 'https://www.google.fr',
            'event[description]' => 'Description',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
