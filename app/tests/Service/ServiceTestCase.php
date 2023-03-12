<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\UserRepository;
use App\Utility\Context;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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
        $reflection = new ReflectionClass($class);
        if ($reflection->isInterface()) {
            $reflection = new ReflectionClass($this->getService($class));
        }

        $constructorArguments = [];
        if ($reflection->getConstructor()) {
            foreach ($reflection->getConstructor()->getParameters() as $param) {
                $constructorArgument = null;
                /** @var class-string<object> */
                $type = (string) $param->getType();
                if (!empty($type)) {
                    $constructorArgument = $this->getService($type);
                }
                if (empty($constructorArgument) && $param->isDefaultValueAvailable()) {
                    $constructorArgument = $param->getDefaultValue();
                }
                $constructorArguments[] = $constructorArgument;
            }
        }

        /** @var MockObject&T */
        $proxy = $this->createTestProxy($reflection->getName(), $constructorArguments);
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
}
