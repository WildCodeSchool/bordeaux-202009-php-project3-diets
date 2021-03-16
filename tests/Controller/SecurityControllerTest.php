<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{

    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/se-connecter');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('div', 'Je me connecte :');
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/se-connecter');
        $form = $crawler->selectButton('Valider')->form([
            'email' => 'johndoe@test.fr',
            'password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/se-connecter');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessFullLogin()
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $csrfToken = static::$container->get('security.csrf.token_manager')->getToken('authenticate');
        $client->request('POST', '/se-connecter', [
            '_csrf_token' => $csrfToken,
            'email' => 'user@gmail.com',
            'password' => 'user'
        ]);
        $this->assertResponseRedirects('/onlogin');
    }
}
