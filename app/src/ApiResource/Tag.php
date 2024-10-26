<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\State\TagsProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model\Operation;

#[ApiResource(
    operations: [
        new Get(
            name: 'tag_list',
            provider: TagsProvider::class,
            openapi: new Operation(
                summary: '',
                description: '',
            ),
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
