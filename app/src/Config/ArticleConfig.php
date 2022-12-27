<?php

declare(strict_types=1);

namespace App\Config;

final class ArticleConfig
{
    public const SLUG_LENGTH = 128;
    public const TITLE_LENGTH = 128;
    public const DESCRIPTION_LENGTH = 255;

    public const OUTPUT = 'ArticleOutput';
}
