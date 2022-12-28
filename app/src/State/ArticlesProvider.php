<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\Article;
use App\Service\ArticleService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class ArticlesProvider implements ProviderInterface
{
    public function __construct(
        private ArticleService $service,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Article
    {
        $result = new Article();
        $author = $context['filters']['author'] ?? null;
        $favorited = $context['filters']['favorited'] ?? null;
        $tag = $context['filters']['tag'] ?? null;
        $result->articlesCount = $this->service->countArticles($author, $favorited, $tag);
        if ($result->articlesCount) {
            $limit = intval($context['filters']['limit'] ?? 20);
            $offset = intval($context['filters']['offset'] ?? 0);
            $result->articles = $this->service->getArticles($limit, $offset, $author, $favorited, $tag);
        }
        return $result;
    }
}
