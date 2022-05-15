<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testCount()
    {
        self::bootKernel();
        $container = static::getContainer();
        $users = $container->get(UserRepository::class)->count([]);
        $this->assertEquals($users, 3);
    }
}
