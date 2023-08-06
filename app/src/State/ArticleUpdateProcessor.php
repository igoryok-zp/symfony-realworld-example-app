<?php

declare(strict_types=1);

namespace App\State;

use App\ApiResource\Article;
use App\Service\ArticleService;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

/**
 * @implements ProcessorInterface<Article>
 */
class ArticleUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        private ArticleService $service,
    ) {
    }

    /**
     * @param Article $data
     * @param Operation $operation
     * @param string[] $uriVariables
     * @param string[][] $context
     * @return Article
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $result = new Article();
        if ($data->article !== null) {
            $result->article = $this->service->updateArticle($uriVariables['slug'], $data->article);
        }
        return $result;
    }
}
