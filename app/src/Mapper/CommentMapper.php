<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\CommentDto;
use App\Entity\Comment;

class CommentMapper
{
    public function __construct(
        private ProfileMapper $profileMapper,
    ) {
    }

    public function mapEntityToDto(Comment $entity): CommentDto
    {
        $result = new CommentDto();
        $result->id = $entity->getId();
        $result->body = $entity->getBody();
        $result->createdAt = $entity->getCreatedAt();
        $result->updatedAt = $entity->getUpdatedAt();
        $result->author = $this->profileMapper->mapEntityToDto($entity->getAuthor());
        return $result;
    }
}
