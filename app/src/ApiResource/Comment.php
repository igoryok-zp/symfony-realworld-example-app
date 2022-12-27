<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\CommentConfig;
use App\Dto\CommentDto;
use App\State\CommentsProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            name: 'comment_list',
            uriTemplate: '/articles/{slug}/comments',
            provider: CommentsProvider::class,
            normalizationContext: [
                'groups' => [
                    CommentConfig::OUTPUT_LIST,
                ],
            ],
            openapiContext: [
                'summary' => '',
                'description' => '',
                'parameters' => [[
                    'name' => 'slug',
                    'in' => 'path',
                    'required' => true,
                    'type' => 'string',
                ]],
            ],
        ),
    ],
)]
final class Comment
{
    #[ApiProperty(
        builtinTypes: [
            new Type(
                builtinType: Type::BUILTIN_TYPE_ARRAY,
                collection: true,
                collectionValueType: [
                    new Type(
                        builtinType: Type::BUILTIN_TYPE_OBJECT,
                        class: CommentDto::class,
                    ),
                ],
            ),
        ],
    )]
    #[Groups([
        CommentConfig::OUTPUT_LIST,
    ])]
    public array $comments = [];
}
