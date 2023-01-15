<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\UserRepository;
use App\Utility\Context;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceTestCase extends KernelTestCase
{
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

        return $this->createTestProxy($reflection->getName(), $constructorArguments);
    }

    protected function createContext(?int $userId = null)
    {
        $user = null;
        if ($userId !== null) {
            $userRepository = $this->getService(UserRepository::class);
            $user = $userRepository->find($userId);
        }
        $context = $this->createMock(Context::class);
        $context->method('getUser')->willReturn($user);
        return $context;
    }

    protected function getService(string $class): mixed
    {
        return static::getContainer()->get($class);
    }
}
