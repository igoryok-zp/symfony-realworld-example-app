<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Config\ArticleConfig;
use App\Dto\ArticleDto;
use App\State\ArticleProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use Symfony\Component\Serializer\Annotation\Groups;

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
    ],
)]
final class Article
{
    #[Groups([
        ArticleConfig::OUTPUT,
    ])]
    public ?ArticleDto $article = null;
}
