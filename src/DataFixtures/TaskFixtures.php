<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        //5 tasks
        for ($i = 1; $i <= 4; $i++) {
            $task = new Task();
            $task->setTitle("title $i")
                ->setContent("content $i");
            $manager->persist($task);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
