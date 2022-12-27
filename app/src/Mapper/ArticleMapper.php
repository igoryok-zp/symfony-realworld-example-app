<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\ArticleDto;
use App\Entity\Article;
use App\Utility\Context;

class ArticleMapper
{
    public function __construct(
        private Context $context,
        private ProfileMapper $profileMapper,
    ) {
    }

    public function mapDtoToEntity(ArticleDto $dto, ?Article $entity = null): Article
    {
        $result = $entity ?: new Article();
        if ($dto->title !== null) {
            $result->setTitle($dto->title);
        }
        if ($dto->description !== null) {
            $result->setDescription($dto->description);
        }
        if ($dto->body !== null) {
            $result->setBody($dto->body);
        }
        if ($result->getAuthor() === null && $this->context->getUser() !== null) {
            $result->setAuthor($this->context->getUser()->getProfile());
        }
        return $result;
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
