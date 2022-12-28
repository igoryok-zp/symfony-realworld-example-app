<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\Tag;
use App\Service\TagService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class TagsProvider implements ProviderInterface
{
    public function __construct(
        private TagService $service,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Tag
    {
        $result = new Tag();
        $result->tags = $this->service->getTags();
        return $result;
    }
}
