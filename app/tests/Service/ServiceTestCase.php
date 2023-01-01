<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Repository\UserRepository;
use App\Utility\Context;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceTestCase extends KernelTestCase
{
    protected function buildProxy(string $originalClassName): mixed
    {
        $reflection = new ReflectionClass($originalClassName);
        if ($reflection->isInterface()) {
            $reflection = new ReflectionClass(static::getContainer()->get($originalClassName));
        }

        $constructorArguments = [];
        if ($reflection->getConstructor()) {
            foreach ($reflection->getConstructor()->getParameters() as $param) {
                $constructorArgument = null;
                $type = (string) $param->getType();
                if (!empty($type)) {
                    $constructorArgument = static::getContainer()->get($type);
                }
                if (empty($constructorArgument) && $param->isDefaultValueAvailable()) {
                    $constructorArgument = $param->getDefaultValue();
                }
                $constructorArguments[] = $constructorArgument;
            }
        }

        $result = $this->createTestProxy($reflection->getName(), $constructorArguments);
        return $result;
    }

    protected function createContext(?int $userId = null)
    {
        $user = null;
        if ($userId !== null) {
            $userRepository = static::getContainer()->get(UserRepository::class);
            $user = $userRepository->find($userId);
        }
        $context = $this->createMock(Context::class);
        $context->method('getUser')->willReturn($user);
        return $context;
    }
}
