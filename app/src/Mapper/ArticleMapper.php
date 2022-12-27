<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\ArticleDto;
use App\Entity\Article;

class ArticleMapper
{
    public function __construct(
        private ProfileMapper $profileMapper,
    ) {
    }

    public function mapEntityToDto(Article $entity): ArticleDto
    {
        $result = new ArticleDto();
        $result->slug = $entity->getSlug();
        $result->title = $entity->getTitle();
        $result->description = $entity->getDescription();
        $result->body = $entity->getBody();
        $result->createdAt = $entity->getCreatedAt();
        $result->updatedAt = $entity->getCreatedAt();
        $result->author = $this->profileMapper->mapEntityToDto($entity->getAuthor());
        return $result;
    }
}
