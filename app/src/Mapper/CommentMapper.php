<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\CommentDto;
use App\Entity\Comment;
use App\Utility\Context;

class CommentMapper
{
    public function __construct(
        private Context $context,
        private ProfileMapper $profileMapper,
    ) {
    }

    public function mapDtoToEntity(CommentDto $dto, ?Comment $entity = null): Comment
    {
        $result = $entity ?: new Comment();
        if ($dto->body !== null) {
            $result->setBody($dto->body);
        }
        if ($result->getAuthor() === null && $this->context->getProfileSafe() !== null) {
            $result->setAuthor($this->context->getProfileSafe());
        }
        return $result;
    }

    public function mapEntityToDto(Comment $entity): CommentDto
    {
        $result = new CommentDto();
        $result->id = $entity->getId();
        $result->body = $entity->getBody();
        $result->createdAt = $entity->getCreatedAt();
        $result->updatedAt = $entity->getUpdatedAt();
        if ($entity->getAuthor() !== null) {
            $result->author = $this->profileMapper->mapEntityToDto($entity->getAuthor());
        }
        return $result;
    }
}
