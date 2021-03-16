<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfilControllerTest extends WebTestCase
{
    public function testEditForCompanyPageOk()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditForAdminPageOk()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditForFreelancerPageOk()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('freelancer@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditForDieteticianPageOk()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('dietetician@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditForFreelancerSubscriberPageOk()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('freelancer@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditForCompanySubscriberPageOk()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edition/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditForAllRoleError()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edit/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testEditIdUnknownError()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edit/50');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testEditBadIdError()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edit/2');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
