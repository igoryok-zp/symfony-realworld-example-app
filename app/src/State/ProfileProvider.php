<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\Profile;
use App\Service\ProfileService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

/**
 * @implements ProviderInterface<Profile>
 */
class ProfileProvider implements ProviderInterface
{
    public function __construct(
        private ProfileService $service,
    ) {
    }

    /**
     * @param Operation $operation
     * @param mixed[] $uriVariables
     * @param mixed[] $context
     * @return Profile|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?Profile
    {
        $result = null;
        $profile = $this->service->getProfile($uriVariables['username']);
        if ($profile !== null) {
            $result = new Profile();
            $result->profile = $profile;
        }
        return $result;
    }
}
