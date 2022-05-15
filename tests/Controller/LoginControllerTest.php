<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testDisplayLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', 'Email:');
        $this->assertSelectorTextContains('button', 'Se connecter');
    }

    public function testLoginSuccessfull(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $client->submitForm('Se connecter', [
            '_username' => 'user1@gmail.com',
            '_password' => 'user1'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertRouteSame('homepage');
        $this->assertSelectorTextContains('html', 'Consulter la liste des tâches à faire');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $client->submitForm('Se connecter', [
            '_username' => 'user1@gmail.com',
            '_password' => 'fakepassword'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('html', 'Invalid credentials');
    }
}
