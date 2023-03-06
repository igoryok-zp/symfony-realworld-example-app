<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Tag;

class TagMapper
{
    public function mapEntityToString(Tag $tag): string
    {
        return (string) $tag->getName();
    }

    /**
     * @param iterable<Tag> $tags
     * @return string[]
     */
    public function mapEntitiesToStringArray(iterable $tags): array
    {
        $result = [];
        foreach ($tags as $tag) {
            $result[] = $this->mapEntityToString($tag);
        }
        sort($result);
        return $result;
    }
}
