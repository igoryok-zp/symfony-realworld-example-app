<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\Comment;
use App\Service\CommentService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class CommentsProvider implements ProviderInterface
{
    public function __construct(
        private CommentService $service,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Comment
    {
        $result = new Comment();
        $result->comments = $this->service->getArticleComments($uriVariables['slug']);
        return $result;
    }
}
