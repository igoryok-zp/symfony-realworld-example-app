<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ArticleDeleteController extends AbstractController
{
    public function __construct(
        private ArticleService $service,
    ) {
    }

    public function __invoke(string $slug): void
    {
        $this->service->deleteArticle($slug);
    }
}
