<?php

declare(strict_types=1);

namespace App\Dto;

use App\Config\CommentConfig;
use App\Config\DateTimeConfig;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/** @SuppressWarnings(PHPMD.ShortVariable) */
final class CommentDto
{
    #[Groups([
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?int $id = null;

    #[Groups([
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?string $body = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?DateTimeImmutable $createdAt = null;

    #[Context([
        DateTimeNormalizer::FORMAT_KEY => DateTimeConfig::FORMAT,
    ])]
    #[Groups([
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?DateTimeImmutable $updatedAt = null;

    #[Groups([
        CommentConfig::OUTPUT_LIST,
    ])]
    public ?ProfileDto $author = null;
}
