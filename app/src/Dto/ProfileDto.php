<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\ArticleConfig;
use App\Config\CommentConfig;
use App\Config\ProfileConfig;
use Symfony\Component\Serializer\Annotation\Groups;

final class ProfileDto
{
    #[Groups([
        ArticleConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
        ProfileConfig::OUTPUT,
    ])]
    public ?string $username = null;

    #[Groups([
        ArticleConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
        ProfileConfig::OUTPUT,
    ])]
    public ?string $bio = null;

    #[Groups([
        ArticleConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
        ProfileConfig::OUTPUT,
    ])]
    public ?string $image = null;

    #[Groups([
        ArticleConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
        ProfileConfig::OUTPUT,
    ])]
    public bool $following = false;
}
