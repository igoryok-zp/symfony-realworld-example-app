<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\User;
use App\Service\UserService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

/**
 * @implements ProviderInterface<User>
 */
class UserCurrentProvider implements ProviderInterface
{
    public function __construct(
        private UserService $service,
    ) {
    }

    /**
     * @param Operation $operation
     * @param string[] $uriVariables
     * @param string[][] $context
     * @return User
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): User
    {
        $result = new User();
        $result->user = $this->service->getCurrentUser();
        return $result;
    }
}
