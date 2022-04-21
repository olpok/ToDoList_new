<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var [UserPasswordHasherInterface]
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {

        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $user1 = new User();

        $password = $this->passwordHasher->hashPassword($user1, 'user1');

        $user1->setPassword($password);
        $user1->setEmail('user1@gmail.com')
            ->setUsername('user1')
            ->setRoles(['ROLE_USER']);

        $manager->persist($user1);




        $manager->flush();
    }

    // $product = new Product();
    // $manager->persist($product);

    // $manager->flush();
    // }
}
