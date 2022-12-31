<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\ArticleConfig;
use App\Config\DateTimeConfig;
use App\Config\TagConfig;
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
        ArticleConfig::OUTPUT_LIST,
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
        ArticleConfig::OUTPUT_LIST,
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
        ArticleConfig::OUTPUT_LIST,
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
        ArticleConfig::OUTPUT_LIST,
    ])]
    public ?string $body = null;

    #[Assert\All(
        constraints: [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(
                max: TagConfig::NAME_LENGTH,
            ),
        ],
        groups: [
            ArticleConfig::VALID,
        ],
    )]
    #[Groups([
        ArticleConfig::INPUT,
        ArticleConfig::OUTPUT,
        ArticleConfig::OUTPUT_LIST,
    ])]
    public ?array $tagList = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        ArticleConfig::OUTPUT,
        ArticleConfig::OUTPUT_LIST,
    ])]
    public ?DateTimeImmutable $createdAt = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        ArticleConfig::OUTPUT,
        ArticleConfig::OUTPUT_LIST,
    ])]
    public ?DateTimeImmutable $updatedAt = null;

    #[Groups([
        ArticleConfig::OUTPUT,
        ArticleConfig::OUTPUT_LIST,
    ])]
    public bool $favorited = false;

    #[Groups([
        ArticleConfig::OUTPUT,
        ArticleConfig::OUTPUT_LIST,
    ])]
    public int $favoritesCount = 0;

    #[Groups([
        ArticleConfig::OUTPUT,
        ArticleConfig::OUTPUT_LIST,
    ])]
    public ?ProfileDto $author = null;
}
