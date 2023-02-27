<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        /** @var UserRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->assertInstanceOf(UserRepository::class, $userRepository);
        $this->userRepository = $userRepository;
    }

    public function testUpgradePasswordException(): void
    {
        $this->expectException(UnsupportedUserException::class);

        $user = $this->createMock(PasswordAuthenticatedUserInterface::class);
        $newHashedPassword = md5('pswd');

        $this->userRepository->upgradePassword($user, $newHashedPassword);
    }

    public function testUpgradePasswordSave(): void
    {
        $user = $this->userRepository->find(1);
        $newHashedPassword = md5('pswd');

        $this->userRepository->upgradePassword($user, $newHashedPassword);

        $this->assertEquals($newHashedPassword, $user->getPassword());
    }
}
