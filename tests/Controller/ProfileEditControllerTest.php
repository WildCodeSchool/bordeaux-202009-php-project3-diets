<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfileEditControllerTest extends WebTestCase
{

    public function testProfilEditForUserPageOk()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/profil/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testProfilEditForAllRoleError()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/profil/edit/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testProfilEditIdUnknownError()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/profil/edit/50');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testProfilEditBadIdError()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/profil/edit/2');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
