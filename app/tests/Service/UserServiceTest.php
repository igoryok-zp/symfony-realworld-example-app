<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Dto\UserDto;
use App\Exception\UnauthorizedException;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends ServiceTestCase
{
    private function createService(?int $contextUserId = null): UserService
    {
        return new UserService(
            $this->createContext($contextUserId),
            $this->buildProxy(UserMapper::class),
            $this->buildProxy(UserPasswordHasherInterface::class),
            $this->buildProxy(UserRepository::class),
            $this->buildProxy(JWTTokenManagerInterface::class),
        );
    }

    public function testGetCurrentUserUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $service = $this->createService();
        $service->getCurrentUser();
    }

    public function testLoginUserUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $data = new UserDto();
        $data->email = 'user1@app.test';
        $data->password = 'test';

        $service = $this->createService();
        $service->loginUser($data);
    }
}
