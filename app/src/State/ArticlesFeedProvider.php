<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\Article;
use App\Service\ArticleService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

/**
 * @implements ProviderInterface<Article>
 */
class ArticlesFeedProvider implements ProviderInterface
{
    public function __construct(
        private ArticleService $service,
    ) {
    }

    /**
     * @param Operation $operation
     * @param mixed[] $uriVariables
     * @param mixed[][] $context
     * @return Article
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Article
    {
        $result = new Article();
        $result->articlesCount = $this->service->countArticlesFeed();
        if ($result->articlesCount) {
            $limit = intval($context['filters']['limit'] ?? 20);
            $offset = intval($context['filters']['offset'] ?? 0);
            $result->articles = $this->service->getArticlesFeed($limit, $offset);
        }
        return $result;
    }
}
