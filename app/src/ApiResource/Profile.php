<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\ProfileConfig;
use App\Controller\Api\ProfileFollowController;
use App\Dto\ProfileDto;
use App\State\ProfileProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            name: 'profile_get',
            uriTemplate: '/profiles/{username}',
            uriVariables: [
                'username' => new Link(
                    fromClass: ProfileDto::class,
                ),
            ],
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
        new Post(
            name: 'profile_follow',
            uriTemplate: '/profiles/{username}/follow',
            controller: ProfileFollowController::class,
            deserialize: false,
            read: false,
            validate: false,
            normalizationContext: [
                'groups' => [
                    ProfileConfig::OUTPUT,
                ],
                'skip_null_values' => false,
            ],
            status: 200,
            openapiContext: [
                'summary' => '',
                'description' => '',
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ),
        new Delete(
            name: 'profile_unfollow',
            uriTemplate: '/profiles/{username}/follow',
            controller: ProfileFollowController::class,
            read: false,
            normalizationContext: [
                'groups' => [
                    ProfileConfig::OUTPUT,
                ],
                'skip_null_values' => false,
            ],
            status: 200,
            openapiContext: [
                'summary' => '',
                'description' => '',
                'responses' => [
                    '200' => [
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Profile-' . ProfileConfig::OUTPUT,
                                ],
                            ],
                            'text/html' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Profile-' . ProfileConfig::OUTPUT,
                                ],
                            ],
                        ],
                    ],
                ],
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
