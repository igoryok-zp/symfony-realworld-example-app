<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\ArticleConfig;
use App\Controller\Api\ArticleDeleteController;
use App\Controller\Api\ArticleFavoriteController;
use App\Dto\ArticleDto;
use App\State\ArticleCreateProcessor;
use App\State\ArticleProvider;
use App\State\ArticlesFeedProvider;
use App\State\ArticlesProvider;
use App\State\ArticleUpdateProcessor;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Get(
            name: 'article_feed',
            uriTemplate: '/articles/feed',
            provider: ArticlesFeedProvider::class,
            normalizationContext: [
                'groups' => [
                    ArticleConfig::OUTPUT_LIST,
                ],
            ],
            openapiContext: [
                'summary' => '',
                'description' => '',
                'parameters' => [[
                    'name' => 'limit',
                    'in' => 'query',
                    'required' => false,
                    'type' => 'integer',
                ], [
                    'name' => 'offset',
                    'in' => 'query',
                    'required' => false,
                    'type' => 'integer',
                ]],
            ],
        ),
        new Get(
            name: 'article_list',
            provider: ArticlesProvider::class,
            normalizationContext: [
                'groups' => [
                    ArticleConfig::OUTPUT_LIST,
                ],
            ],
            openapiContext: [
                'summary' => '',
                'description' => '',
                'parameters' => [[
                    'name' => 'author',
                    'in' => 'query',
                    'required' => false,
                    'type' => 'string',
                ], [
                    'name' => 'favorited',
                    'in' => 'query',
                    'required' => false,
                    'type' => 'string',
                ], [
                    'name' => 'tag',
                    'in' => 'query',
                    'required' => false,
                    'type' => 'string',
                ], [
                    'name' => 'limit',
                    'in' => 'query',
                    'required' => false,
                    'type' => 'integer',
                ], [
                    'name' => 'offset',
                    'in' => 'query',
                    'required' => false,
                    'type' => 'integer',
                ]],
            ],
        ),
        new Get(
            name: 'article_get',
            uriTemplate: '/articles/{slug}',
            uriVariables: [
                'slug' => new Link(
                    fromClass: ArticleDto::class,
                    fromProperty: 'slug'
                ),
            ],
            provider: ArticleProvider::class,
            normalizationContext: [
                'groups' => [
                    ArticleConfig::OUTPUT,
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
        new Post(
            name: 'article_create',
            processor: ArticleCreateProcessor::class,
            normalizationContext: [
                'groups' => [
                    ArticleConfig::OUTPUT,
                ],
            ],
            denormalizationContext: [
                'groups' => [
                    ArticleConfig::INPUT,
                ],
            ],
            validationContext: [
                'groups' => [
                    ArticleConfig::VALID_CREATE,
                    ArticleConfig::VALID,
                ],
            ],
            openapiContext: [
                'summary' => '',
                'description' => '',
            ],
        ),
        new Put(
            name: 'article_update',
            uriTemplate: '/articles/{slug}',
            read: false,
            processor: ArticleUpdateProcessor::class,
            normalizationContext: [
                'groups' => [
                    ArticleConfig::OUTPUT,
                ],
            ],
            denormalizationContext: [
                'groups' => [
                    ArticleConfig::INPUT,
                ],
            ],
            validationContext: [
                'groups' => [
                    ArticleConfig::VALID,
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
        new Delete(
            name: 'article_delete',
            uriTemplate: '/articles/{slug}',
            controller: ArticleDeleteController::class,
            read: false,
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
        new Post(
            name: 'article_favorite',
            uriTemplate: '/articles/{slug}/favorite',
            controller: ArticleFavoriteController::class,
            deserialize: false,
            read: false,
            validate: false,
            normalizationContext: [
                'groups' => [
                    ArticleConfig::OUTPUT,
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
                'requestBody' => [
                    'content' => [],
                ],
            ],
        ),
        new Delete(
            name: 'article_unfavorite',
            uriTemplate: '/articles/{slug}/favorite',
            controller: ArticleFavoriteController::class,
            read: false,
            normalizationContext: [
                'groups' => [
                    ArticleConfig::OUTPUT,
                ],
            ],
            status: 200,
            openapiContext: [
                'summary' => '',
                'description' => '',
                'parameters' => [[
                    'name' => 'slug',
                    'in' => 'path',
                    'required' => true,
                    'type' => 'string',
                ]],
                'responses' => [
                    '200' => [
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Article-' . ArticleConfig::OUTPUT,
                                ],
                            ],
                            'text/html' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Article-' . ArticleConfig::OUTPUT,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ),
    ],
)]
final class Article
{
    /**
     * @var ArticleDto[]
     */
    #[ApiProperty(
        builtinTypes: [
            new Type(
                builtinType: Type::BUILTIN_TYPE_ARRAY,
                collection: true,
                collectionValueType: [
                    new Type(
                        builtinType: Type::BUILTIN_TYPE_OBJECT,
                        class: ArticleDto::class,
                    ),
                ],
            ),
        ],
    )]
    #[Groups([
        ArticleConfig::OUTPUT_LIST,
    ])]
    public array $articles = [];

    #[Groups([
        ArticleConfig::OUTPUT_LIST,
    ])]
    public int $articlesCount = 0;

    #[Assert\Valid]
    #[Groups([
        ArticleConfig::INPUT,
        ArticleConfig::OUTPUT,
    ])]
    public ?ArticleDto $article = null;
}
