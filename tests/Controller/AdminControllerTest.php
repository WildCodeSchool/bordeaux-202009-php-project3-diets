<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends WebTestCase
{

    public function testAdminPage()
    {
        $client = static::createClient();
        $client->request('GET', '/administrateur');
        $this->assertResponseRedirects('/se-connecter');
    }

    public function testAdminPageError()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testLetAdminRequireAdminRoleWithRoleCompany()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($testUser);

        $client->request('GET', '/profil/edition/' . $testUser->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testLetAdminRequireAdminRoleWithRoleAdmin()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($testUser);


        $client->request('GET', '/administrateur');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}
