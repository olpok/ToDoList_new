<?php

namespace App\Tests\Controller;

use Generator;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Tests\AuthenticationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testVisitingWhileLoggedIn(): void
    {
        $client = static::createAuthenticatedUserClient();
        $client->request('GET', '/tasks');

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('html', 'Créer une tâche');
        $this->assertSelectorExists('html', 'Supprimer');
    }

    public function testVisitingDoneWhileLoggedIn(): void
    {
        $client = static::createAuthenticatedUserClient();

        $crawler = $client->request('GET', '/');

        $link =  $crawler->selectLink('Consulter la liste des tâches terminées')->link();
        $crawler = $client->click($link);

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_index_done');
    }

    public function testCreateTaskSuccessfull()
    {
        $client = static::createAuthenticatedUserClient();
        $crawler = $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'task[title]'    => 'title',
            'task[content]' => 'content',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);

        $client->followRedirect();
        $this->assertRouteSame('task_index');
        $this->assertSelectorExists('.alert.alert-success', 'La tâche a bien été ajoutée.');
    }

    /**
     * @dataProvider provideFailed
     * @param array $formData
     */
    public function testCreateTaskFailed($formdata)
    {
        $client = static::createAuthenticatedUserClient();
        $crawler = $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        $client->submit(
            $form,
            $formdata
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function provideFailed(): Generator
    {
        yield [
            [
                'task[title]'    => '',
                'task[content]' => 'content',
            ]
        ];
        yield [
            [
                'task[title]'    => 'title',
                'task[content]' => '',
            ]
        ];
    }

    public function testUpdateTaskSuccessfull()
    {
        $client = static::createAuthenticatedUserClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy([]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_edit", ["id" => $task->getId()])
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Editer tâche');

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Modifier');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'task[title]'    => 'title',
            'task[content]' => 'content',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_SEE_OTHER);

        $client->followRedirect();
        $this->assertRouteSame('task_index');
        $this->assertSelectorExists('.alert.alert-success', 'La tâche a bien été modifiée.');
    }

    public function testToggle()
    {
        $client = static::createAuthenticatedUserClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy([]);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_toggle", ["id" => $task->getId()])
        );

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_index');
        $this->assertSelectorExists('.alert.alert-success', 'La tâche %s a bien été marquée comme faite.');
    }

    public function testDeleteBySameUser()
    {
        $client = static::createAuthenticatedUserClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneById(1);

        /*   $client->request('GET', '/tasks'); //check route
        $this->assertRouteSame('task_index'); //ok*/

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_edit", ["id" => $task->getId()])
        ); // check edit route

        /*    $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_delete", ["id" => $task->getId()])
        );*/

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Supprimer');

        $form = $buttonCrawlerNode->form();

        $client->submit($form);

        /*   $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_delete", ["id" => $task->getId()])
        );*/

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_index');
        $this->assertSelectorExists('.alert.alert-success', 'La tâche a bien été supprimée.');
    }

    public function testDeleteFailedNotBySameUser()
    {
        $client = static::createAuthenticatedUserClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneById(3);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_edit", ["id" => $task->getId()])
        ); // check edit route

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Supprimer');

        $form = $buttonCrawlerNode->form();

        $client->submit($form);

        /*  $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("task_delete", ["id" => $task->getId()])
        );*/

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
