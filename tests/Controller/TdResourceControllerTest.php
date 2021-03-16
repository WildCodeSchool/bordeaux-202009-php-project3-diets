<?php

namespace App\Tests\Controller;

use App\Entity\Pathology;
use App\Entity\ResourceFormat;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TdResourceControllerTest extends WebTestCase
{
    public function testResourceForm()
    {
        $format = new ResourceFormat();
        $format->setFormat('1');

        $pathology = new Pathology();
        $pathology->setName('pdf');

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