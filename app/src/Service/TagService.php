<?php

declare(strict_types=1);

namespace App\Service;

use App\Mapper\TagMapper;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(
        private TagMapper $tagMapper,
        private TagRepository $tagRepository,
    ) {
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        $tags = $this->tagRepository->findAll();
        return $this->tagMapper->mapEntitiesToStringArray($tags);
    }
}
