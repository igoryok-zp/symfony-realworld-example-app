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
use Symfony\Component\Validator\Constraints as Assert;

final class ArticleDto
{
    #[ApiProperty(identifier: true)]
    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?string $slug = null;

    #[Assert\Length(
        max: ArticleConfig::TITLE_LENGTH,
        groups: [
            ArticleConfig::VALID,
        ],
    )]
    #[Assert\NotBlank(
        groups: [
            ArticleConfig::VALID_CREATE,
        ]
    )]
    #[Groups([
        ArticleConfig::INPUT,
        ArticleConfig::OUTPUT,
    ])]
    public ?string $title = null;

    #[Assert\Length(
        max: ArticleConfig::DESCRIPTION_LENGTH,
        groups: [
            ArticleConfig::VALID,
        ],
    )]
    #[Assert\NotBlank(
        groups: [
            ArticleConfig::VALID_CREATE,
        ],
    )]
    #[Groups([
        ArticleConfig::INPUT,
        ArticleConfig::OUTPUT,
    ])]
    public ?string $description = null;

    #[Assert\NotBlank(
        groups: [
            ArticleConfig::VALID_CREATE,
        ]
    )]
    #[Groups([
        ArticleConfig::INPUT,
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
    public bool $favorited = false;

    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public int $favoritesCount = 0;

    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?ProfileDto $author = null;
}
