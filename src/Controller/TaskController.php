<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tasks')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(
                ['isDone' => false]
            ),
        ]);
    }

    #[Route('/done', name: 'task_index_done', methods: ['GET'])]
    public function indexDone(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(
                ['isDone' => true]
            ),
        ]);
    }

    #[Route('/create', name: 'task_create', methods: ['GET', 'POST'])]
    public function new(Request $request, TaskRepository $taskRepository): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $user = $this->getUser(); //fetching the User Object of the current User after authentication 

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($user);
            $taskRepository->add($task);
            $this->addFlash('success', 'La tâche a bien été ajoutée.');
            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/toggle', name: 'task_toggle')]
    public function toggle(Task $task, EntityManagerInterface $entityManager)
    {
        $task->toggle(!$task->isDone());
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_index');
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->add($task);
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'task_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if (
            $task->getUser() == $this->getUser() //the same user
        ) {
            if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
                $taskRepository->remove($task);
                $this->addFlash('success', 'La tâche a bien été supprimée.');
            }
            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($task->getUser() == NULL) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            // anonymous user deleted by admin
            if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
                $taskRepository->remove($task);
                $this->addFlash('success', 'La tâche a bien été supprimée.');
            }
            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        // try to delete not by the same user or not anonumous by admin
        $request->getSession()->getFlashBag()->add('note', 'Vous ne pouvez pas effectuer cette opération.');

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }
}
