<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\UserRepository;
use App\Utility\Context;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ServiceTestCase extends KernelTestCase
{
    /**
     * @template T of object
     * @param class-string<T> $class
     * @return MockObject&T
     */
    protected function buildProxy(string $class): mixed
    {
        $service = $this->getService($class);
        $reflection = new ReflectionClass($service);

        $proxy = $this->createMock($reflection->getName());
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $callback = [$service, $method->getName()];
            if (!$method->isConstructor() && !$method->isDestructor() && is_callable($callback)) {
                $proxy->method($method->getName())
                    ->willReturnCallback(
                        fn() => call_user_func_array($callback, func_get_args())
                    );
            }
        }

        return $proxy;
    }

    protected function createContext(?int $userId = null): Context
    {
        $user = null;
        if ($userId !== null) {
            $userRepository = $this->getService(UserRepository::class);
            $user = $userRepository->find($userId);
        }

        /** @var MockObject&TokenInterface */
        $tokenMock = $this->createMock(TokenInterface::class);
        $tokenMock->method('getUser')->willReturn($user);

        /** @var MockObject&TokenStorageInterface */
        $tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $tokenStorageMock->method('getToken')->willReturn($tokenMock);

        $context = new Context($tokenStorageMock);
        return $context;
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    protected function getService(string $class): mixed
    {
        /** @var T */
        $service = static::getContainer()->get($class);
        $this->assertInstanceOf($class, $service);
        return $service;
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T|null
     */
    protected function getServiceOrNull(string $class): mixed
    {
        /** @var T|null */
        $service = static::getContainer()->get(
            $class,
            ContainerInterface::NULL_ON_INVALID_REFERENCE
        );
        return $service;
    }
}
