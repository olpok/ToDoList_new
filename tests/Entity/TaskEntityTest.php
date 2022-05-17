<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskEntityTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testTaskCreate(): void
    {
        $task = new Task(); //create Task object
        $task->setTitle("Title");
        $task->setContent("Content");
        $task->setCreatedAt(new \DateTimeImmutable());

        $user = new User(); // Create User object.
        $user->setUsername("Name");
        $user->setPassword("$2y$13\$gnQkicSi9Er8SFD90QslHu135MYHU47N/dAkiHsym/UKOTK1.1FlO");
        $user->setEmail('user1@gmail.com')
            ->setRoles(['ROLE_USER']);
        $task->setUser($user);


        $this->assertEquals("Title", $task->getTitle());
        $this->assertEquals("Content", $task->getContent());
        $this->assertEquals(false, $task->isDone());
        $this->assertEquals(null, $task->toggle(true));
        $this->assertEquals(null, $task->getId());
        $this->assertEquals($user, $task->getUser());

        $current_time = date("Y-m-d H:i:s");
        $createdAt = date_format($task->getCreatedAt(), "Y-m-d H:i:s");

        $this->assertEquals($current_time, $createdAt);
    }
}
