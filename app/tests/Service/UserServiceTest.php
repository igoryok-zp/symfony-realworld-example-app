<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Dto\UserDto;
use App\Exception\UnauthorizedException;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends ServiceTestCase
{
    /**
     * @var MockObject&JWTTokenManagerInterface
     */
    private $tokenManager;

    private function createService(?int $contextUserId = null): UserService
    {
        $this->tokenManager = $this->buildProxy(JWTTokenManagerInterface::class);

        return new UserService(
            $this->createContext($contextUserId),
            $this->getService(UserMapper::class),
            $this->getService(UserPasswordHasherInterface::class),
            $this->getService(UserRepository::class),
            $this->tokenManager,
        );
    }

    private function expectTokenManagerCreateNever(): void
    {
        $this->tokenManager
            ->expects($this->never())
            ->method('create');
    }

    public function testGetCurrentUserUnauthorized(): void
    {
        $this->expectException(UnauthorizedException::class);

        $service = $this->createService();

        $this->expectTokenManagerCreateNever();

        $service->getCurrentUser();
    }

    public function testLoginUserUnauthorized(): void
    {
        $this->expectException(UnauthorizedException::class);

        $data = new UserDto();
        $data->email = 'user1@app.test';
        $data->password = 'test';

        $service = $this->createService();

        $this->expectTokenManagerCreateNever();

        $service->loginUser($data);
    }
}
