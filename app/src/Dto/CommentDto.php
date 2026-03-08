<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\CommentConfig;
use App\Config\DateTimeConfig;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

/** @SuppressWarnings(PHPMD.ShortVariable) */
final class CommentDto
{
    #[Groups([
        CommentConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?int $id = null;

    #[Assert\NotBlank(
        groups: [
            CommentConfig::VALID,
        ],
    )]
    #[Groups([
        CommentConfig::INPUT,
        CommentConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?string $body = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        CommentConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?DateTimeImmutable $createdAt = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        CommentConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?DateTimeImmutable $updatedAt = null;

    #[Groups([
        CommentConfig::OUTPUT,
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?ProfileDto $author = null;
}
