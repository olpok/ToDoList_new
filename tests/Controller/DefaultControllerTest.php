<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testH1Index(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort ');
        $this->assertSelectorExists('a[href]');
    }

    public function testIfHrefExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // $this->assertSelectorTextContains('a[href]', 'Créer une nouvelle tâche');
        $this->assertSelectorExists('a[href]');
        // $this->assertSelectorTextContains('a[href]', 'path(\'task_create\')');
        // $this->assertSelectorExists('a[href *= "task_create"]');
    }
}
