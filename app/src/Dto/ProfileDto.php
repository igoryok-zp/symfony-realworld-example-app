<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\ArticleConfig;
use App\Config\ProfileConfig;
use Symfony\Component\Serializer\Annotation\Groups;

final class ProfileDto
{
    #[Groups([
        ArticleConfig::OUTPUT,
        ProfileConfig::OUTPUT,
    ])]
    public ?string $username = null;

    #[Groups([
        ArticleConfig::OUTPUT,
        ProfileConfig::OUTPUT,
    ])]
    public ?string $bio = null;

    #[Groups([
        ArticleConfig::OUTPUT,
        ProfileConfig::OUTPUT,
    ])]
    public ?string $image = null;

    #[Groups([
        ArticleConfig::OUTPUT,
        ProfileConfig::OUTPUT,
    ])]
    public bool $following = false;
}
