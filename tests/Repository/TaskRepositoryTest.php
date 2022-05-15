<?php

namespace App\Tests\Repository;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    public function testCount()
    {
        self::bootKernel();
        $container = static::getContainer();
        $tasks = $container->get(TaskRepository::class)->count([]);
        $this->assertEquals($tasks, 4);
    }
}
