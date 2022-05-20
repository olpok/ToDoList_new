<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testH1Index(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort ');
    }

    public function testIfHrefExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('html', 'Créer un utilisateur');
        $this->assertSelectorExists('a[href]');
    }

    public function testItShowsTheFormToTheUserAndRedirectsIfLoginSuccessfully(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link =  $crawler->selectLink('Se connecter')->link();
        $crawler = $client->click($link);

        $this->assertSelectorTextContains('label', 'Email:');
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('login');

        $crawler = $client->submitForm('Se connecter', [
            '_username' => 'user1@gmail.com',
            '_password' => 'user1'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertRouteSame('homepage');
        $this->assertSelectorTextContains('html', 'Consulter la liste des tâches à faire');
    }
}
