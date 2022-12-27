<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\ArticleConfig;
use App\Config\DateTimeConfig;
use ApiPlatform\Metadata\ApiProperty;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class ArticleDto
{
    #[ApiProperty(identifier: true)]
    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?string $slug = null;

    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?string $title = null;

    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?string $description = null;

    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?string $body = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?DateTimeImmutable $createdAt = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?DateTimeImmutable $updatedAt = null;

    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?ProfileDto $author = null;
}
