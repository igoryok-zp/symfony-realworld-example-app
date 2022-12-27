<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\ProfileConfig;
use App\Dto\ProfileDto;
use App\State\ProfileProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            name: 'profile_get',
            uriTemplate: '/profiles/{username}',
            provider: ProfileProvider::class,
            normalizationContext: [
                'groups' => [
                    ProfileConfig::OUTPUT,
                ],
                'skip_null_values' => false,
            ],
            openapiContext: [
                'summary' => '',
                'description' => '',
            ],
        ),
    ],
)]
final class Profile
{
    #[Groups([
        ProfileConfig::OUTPUT,
    ])]
    public ?ProfileDto $profile = null;
}
