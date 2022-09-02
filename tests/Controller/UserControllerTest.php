<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Tests\AuthenticationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testIndexUsersRedirectToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        $this->assertResponseRedirects();

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND); //redirect from app_user_index to login
        $client->followRedirect();
        $this->assertRouteSame('login');
        $this->assertSelectorTextContains('button', 'Se connecter');  // check if page login //ok
    }

    public function testVisitingWhileLoggedInAdmin(): void
    {
        $client = static::createAuthenticatedAdminClient();

        $client->request('GET', '/admin');

        //  $this->assertResponseStatusCodeSame(Response::HTTP_MOVED_PERMANENTLY);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('app_user_index');
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testVisitingWhileLoggedInUser(): void //ok mais gestion erreur
    {
        $client = static::createAuthenticatedUserClient();

        $client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUserSuccessfull()
    {
        $client = static::createAuthenticatedAdminClient();
        $crawler = $client->request('GET', '/admin/new');

        $this->assertResponseIsSuccessful();

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Enregistrer');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'user[username]'    => 'username',
            'user[email]' => 'username@gmail.com',
            'user[roles]'    => 'ROLE_ADMIN',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password'
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);

        $client->followRedirect();
        $this->assertRouteSame('app_user_index');
        $this->assertSelectorExists('.alert.alert-success', 'L\'utilisateur a bien été ajouté.');
    }

    public function testUpdateUserSuccessfull()
    {
        $client = static::createAuthenticatedAdminClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy([]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("app_user_edit", ["id" => $user->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Editer utilisateur');

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Modifier');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'user[username]'    => 'username',
            'user[email]' => 'username@gmail.com',
            'user[roles]'    => 'ROLE_ADMIN',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password'
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);
        $client->followRedirect();
        $this->assertRouteSame('app_user_index');
        $this->assertSelectorExists('.alert.alert-success', 'L\'utilisateur a bien été modifié.');
    }

    public function testDeleteSuccessfull()
    {
        $client = static::createAuthenticatedAdminClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneById([3]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("app_user_edit", ["id" => $user->getId()])
        ); // check edit route

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Editer utilisateur'); //ok edit route

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Supprimer');

        $form = $buttonCrawlerNode->form();

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertRouteSame('app_user_index');

        $this->assertSelectorExists('', 'User supprimé avec success');
        //$this->assertSelectorExists('.alert.alert-success', 'User supprimé avec success');
    }
}
