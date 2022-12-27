<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\ArticleConfig;
use App\Controller\Api\ArticleDeleteController;
use App\Dto\ArticleDto;
use App\State\ArticleCreateProcessor;
use App\State\ArticleProvider;
use App\State\ArticleUpdateProcessor;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
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
    ],
)]
final class Article
{
    #[Assert\Valid]
    #[Groups([
        ArticleConfig::INPUT,
        ArticleConfig::OUTPUT,
    ])]
    public ?ArticleDto $article = null;
}
