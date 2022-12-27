<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CommentDeleteController extends AbstractController
{
    public function __construct(
        private CommentService $service,
    ) {
    }

    /** @SuppressWarnings(PHPMD.ShortVariable) */
    public function __invoke(string $slug, int $id)
    {
        $this->service->deleteArticleComment($slug, $id);
    }
}
