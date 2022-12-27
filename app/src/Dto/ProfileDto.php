<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\ProfileConfig;
use Symfony\Component\Serializer\Annotation\Groups;

final class ProfileDto
{
    #[Groups([
        ProfileConfig::OUTPUT,
    ])]
    public ?string $username = null;

    #[Groups([
        ProfileConfig::OUTPUT,
    ])]
    public ?string $bio = null;

    #[Groups([
        ProfileConfig::OUTPUT,
    ])]
    public ?string $image = null;
}
