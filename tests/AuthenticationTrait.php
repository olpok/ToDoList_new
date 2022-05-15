<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Trait AuthenticationTrait
 * @package App\Tests
 */
trait AuthenticationTrait
{
    /**
     * @return KernelBrowser
     */
    public static function createAuthenticatedAdminClient(): KernelBrowser
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin1@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        return $client;
    }

    /**
     * @return KernelBrowser
     */
    public static function createAuthenticatedUserClient(): KernelBrowser
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user1@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        return $client;
    }
}
