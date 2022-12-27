<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\User;
use App\Service\UserService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

class UserUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        private UserService $service,
    ) {
    }

    /**
     * @param User $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return User
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $result = new User();
        $result->user = $this->service->updateCurrentUser($data->user);
        return $result;
    }
}
