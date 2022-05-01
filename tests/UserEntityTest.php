<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
  public function testUserCreate(): void
  {
    $user = new User(); // Create User object.
    $user->setUsername("Name");
    $user->setPassword("$2y$13\$gnQkicSi9Er8SFD90QslHu135MYHU47N/dAkiHsym/UKOTK1.1FlO");
    $user->setEmail('user1@gmail.com')
      ->setRoles(['ROLE_USER']);

    $task = new Task(); //create Task object
    $task->setTitle("Title");
    $task->setContent("Content");
    $task->setCreatedAt(new \DateTimeImmutable());
    $user->addTask($task);


    $this->assertEquals("Name", $user->getUserName());
    $this->assertEquals("$2y$13\$gnQkicSi9Er8SFD90QslHu135MYHU47N/dAkiHsym/UKOTK1.1FlO", $user->getPassword());
    $this->assertEquals("user1@gmail.com", $user->getEmail());
    $this->assertEquals(['ROLE_USER'], $user->getRoles());
    $this->assertEquals(null, $user->getId()); // ?
    $taskCollection = $user->getTasks();
    $this->assertEquals('Title', $taskCollection->last()->getTitle());

    $user->removeTask($task);    // Remove the task.
    $taskCollection = $user->getTasks();
    $this->assertEquals(true, $taskCollection->isEmpty());

    // $this->assertEquals("Title", $task->getTitle());
    // $this->assertEquals("Content", $task->getContent());
  }
}
