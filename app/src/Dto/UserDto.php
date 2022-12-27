<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\UserConfig;
use App\Validator as AppAssert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UserDto
{
    #[AppAssert\UniqueEmail(
        groups: [
            UserConfig::VALID_CREATE,
            UserConfig::VALID_UPDATE,
        ],
    )]
    #[Assert\Email(
        groups: [
            UserConfig::VALID,
        ],
    )]
    #[Assert\Length(
        max: UserConfig::EMAIL_LENGTH,
        groups: [
            UserConfig::VALID,
        ],
    )]
    #[Assert\NotBlank(
        groups: [
            UserConfig::VALID_CREATE,
            UserConfig::VALID_LOGIN,
        ],
    )]
    #[Groups([
        UserConfig::INPUT,
        UserConfig::OUTPUT,
    ])]
    public ?string $email = null;

    #[Assert\NotBlank(
        groups: [
            UserConfig::VALID_CREATE,
            UserConfig::VALID_LOGIN,
        ],
    )]
    #[Groups([
        UserConfig::INPUT_CREATE,
        UserConfig::INPUT_LOGIN,
        UserConfig::INPUT_UPDATE,
    ])]
    public ?string $password = null;

    #[Groups([
        UserConfig::OUTPUT,
    ])]
    public ?string $token = null;
}
