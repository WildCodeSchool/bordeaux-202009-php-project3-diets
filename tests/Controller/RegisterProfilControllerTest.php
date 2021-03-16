<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterProfilControllerTest extends WebTestCase
{

    public function testChoiceRoleWithUserId()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('user@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/choix-statut/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegisterDietWithUserId()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('dietetician@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/inscription/dieteticien/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegisterCompanyWithUserId()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('company@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/inscription/societe/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegisterFreelancerWithUserId()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('freelancer@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/inscription/auto-entrepreneur/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegisterFreelancerWithBadId()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('freelancer@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/inscription/auto-entrepreneur/2');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
