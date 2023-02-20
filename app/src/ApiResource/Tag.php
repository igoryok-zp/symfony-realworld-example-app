<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\State\TagsProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;

#[ApiResource(
    operations: [
        new Get(
            name: 'tag_list',
            provider: TagsProvider::class,
            openapiContext: [
                'summary' => '',
                'description' => '',
            ],
        ),
    ],
)]
final class Tag
{
    /**
     * @var string[]
     */
    public array $tags = [];
}
