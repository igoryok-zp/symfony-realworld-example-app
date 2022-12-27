<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ArticleDto;
use App\Entity\Article;
use App\Exception\NotFoundException;
use App\Mapper\ArticleMapper;
use App\Repository\ArticleRepository;

class ArticleService
{
    public function __construct(
        private ArticleMapper $articleMapper,
        private ArticleRepository $articleRepository,
    ) {
    }

    private function toDto(Article $article): ArticleDto
    {
        return $this->articleMapper->mapEntityToDto($article);
    }

    /** @SuppressWarnings(PHPMD.BooleanArgumentFlag) */
    private function findArticle(string $slug, bool $safe = false): ?Article
    {
        $article = $this->articleRepository->findOneBySlug($slug);
        if ($article === null && !$safe) {
            throw new NotFoundException('Article "' . $slug . '" does not exist');
        }
        return $article;
    }

    private function save(ArticleDto $data, ?Article $article = null): Article
    {
        $result = $this->articleMapper->mapDtoToEntity($data, $article);
        $this->articleRepository->save($result);
        return $result;
    }

    public function getArticle(string $slug): ?ArticleDto
    {
        $result = null;
        $article = $this->findArticle($slug, true);
        if ($article !== null) {
            $result = $this->toDto($article);
        }
        return $result;
    }

    public function createArticle(ArticleDto $data): ArticleDto
    {
        $article = $this->save($data);
        return $this->toDto($article);
    }
}
