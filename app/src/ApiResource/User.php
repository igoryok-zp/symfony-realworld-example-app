<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\UserConfig;
use App\Dto\UserDto;
use App\State\UserCreateProcessor;
use App\State\UserCurrentProvider;
use App\State\UserLoginProcessor;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            name: 'user_create',
            processor: UserCreateProcessor::class,
            normalizationContext: [
                'groups' => [
                    UserConfig::OUTPUT,
                ],
                'skip_null_values' => false,
            ],
            denormalizationContext: [
                'groups' => [
                    UserConfig::INPUT_CREATE,
                    UserConfig::INPUT,
                ],
            ],
            validationContext: [
                'groups' => [
                    UserConfig::VALID_CREATE,
                    UserConfig::VALID,
                ]
            ],
            openapiContext: [
                'summary' => '',
                'description' => '',
            ],
        ),
        new Post(
            name: 'user_login',
            uriTemplate: '/users/login',
            processor: UserLoginProcessor::class,
            normalizationContext: [
                'groups' => [
                    UserConfig::OUTPUT,
                ],
                'skip_null_values' => false,
            ],
            denormalizationContext: [
                'groups' => [
                    UserConfig::INPUT_LOGIN,
                    UserConfig::INPUT,
                ],
            ],
            validationContext: [
                'groups' => [
                    UserConfig::VALID_LOGIN,
                    UserConfig::VALID,
                ],
            ],
            openapiContext: [
                'summary' => '',
                'description' => '',
            ],
        ),
        new Get(
            name: 'user_current',
            uriTemplate: '/user',
            provider: UserCurrentProvider::class,
            normalizationContext: [
                'groups' => [
                    UserConfig::OUTPUT,
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
final class User
{
    #[Assert\Valid]
    #[Groups([
        UserConfig::INPUT,
        UserConfig::OUTPUT,
    ])]
    public ?UserDto $user = null;
}
