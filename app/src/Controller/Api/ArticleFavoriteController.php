<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\ApiResource\Article;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ArticleFavoriteController extends AbstractController
{
    public function __construct(
        private ArticleService $service,
    ) {
    }

    public function __invoke(string $slug, Request $request): Article
    {
        $result = new Article();
        $result->article = $request->getMethod() !== 'DELETE'
            ? $this->service->favoriteArticle($slug)
            : $this->service->unfavoriteArticle($slug);
        return $result;
    }
}
