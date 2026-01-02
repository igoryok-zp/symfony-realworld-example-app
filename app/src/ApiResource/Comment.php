<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\CommentConfig;
use App\Controller\Api\CommentCreateController;
use App\Controller\Api\CommentDeleteController;
use App\Dto\CommentDto;
use App\State\CommentsProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
            openapi: new Operation(
                summary: '',
                description: '',
                parameters: [
                    new Parameter(
                        name: 'slug',
                        in: 'path',
                        required: true,
                        schema: ['type' => 'string'],
                    ),
                ],
            ),
        ),
        new Post(
            name: 'comment_create',
            uriTemplate: '/articles/{slug}/comments',
            controller: CommentCreateController::class,
            read: false,
            normalizationContext: [
                'groups' => [
                    CommentConfig::OUTPUT,
                ],
            ],
            denormalizationContext: [
                'groups' => [
                    CommentConfig::INPUT,
                ],
            ],
            validationContext: [
                'groups' => [
                    CommentConfig::VALID,
                ],
            ],
            openapi: new Operation(
                summary: '',
                description: '',
                parameters: [
                    new Parameter(
                        name: 'slug',
                        in: 'path',
                        required: true,
                        schema: ['type' => 'string'],
                    ),
                ],
            ),
        ),
        new Delete(
            name: 'comment_delete',
            uriTemplate: '/articles/{slug}/comments/{id}',
            controller: CommentDeleteController::class,
            read: false,
            openapi: new Operation(
                summary: '',
                description: '',
                parameters: [
                    new Parameter(
                        name: 'slug',
                        in: 'path',
                        required: true,
                        schema: ['type' => 'string'],
                    ),
                    new Parameter(
                        name: 'id',
                        in: 'path',
                        required: true,
                        schema: ['type' => 'integer'],
                    ),
                ],
            ),
        ),
    ],
)]
final class Comment
{
    #[ApiProperty(identifier: true)]
    public ?string $slug = null;

    /**
     * @var CommentDto[]
     */
    #[ApiProperty(
        builtinTypes: [
            new Type(
                builtinType: Type::BUILTIN_TYPE_ARRAY,
                collection: true,
                collectionKeyType: new Type(Type::BUILTIN_TYPE_INT),
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

    #[Assert\Valid]
    #[Groups([
        CommentConfig::INPUT,
        CommentConfig::OUTPUT,
    ])]
    public ?CommentDto $comment = null;
}
