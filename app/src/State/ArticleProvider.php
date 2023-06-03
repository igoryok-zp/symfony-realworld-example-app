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
class ArticleProvider implements ProviderInterface
{
    public function __construct(
        private ArticleService $service,
    ) {
    }

    /**
     * @param Operation $operation
     * @param string[] $uriVariables
     * @param string[][] $context
     * @return Article|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?Article
    {
        $result = null;
        $article = $this->service->getArticle($uriVariables['slug']);
        if ($article !== null) {
            $result = new Article();
            $result->article = $article;
        }
        return $result;
    }
}
