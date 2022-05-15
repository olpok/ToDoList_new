<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
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

        $user3 = new User();

        $password = $this->passwordHasher->hashPassword($user3, 'user3');

        $user3->setPassword($password);
        $user3->setEmail('user3@gmail.com')
            ->setUsername('user3')
            ->setRoles(['ROLE_USER']);

        $manager->persist($user3);

        $admin1 = new User();

        $password = $this->passwordHasher->hashPassword($admin1, 'admin1');

        $admin1->setPassword($password);
        $admin1->setEmail('admin1@gmail.com')
            ->setUsername('admin1')
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin1);

        $manager->flush();
    }

    // $product = new Product();
    // $manager->persist($product);

    // $manager->flush();
    // }
}
