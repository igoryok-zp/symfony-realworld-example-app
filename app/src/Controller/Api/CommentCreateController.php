<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\ApiResource\Comment;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CommentCreateController extends AbstractController
{
    public function __construct(
        private CommentService $service,
    ) {
    }

    public function __invoke(string $slug, Comment $data): Comment
    {
        $result = new Comment();
        if ($data->comment !== null) {
            $result->comment = $this->service->createArticleComment($slug, $data->comment);
        }
        return $result;
    }
}
