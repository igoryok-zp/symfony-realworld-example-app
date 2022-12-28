<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ArticleDto;
use App\Entity\Article;
use App\Entity\Profile;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Mapper\ArticleMapper;
use App\Repository\ArticleRepository;
use App\Repository\FavoriteRepository;
use App\Utility\Context;

class ArticleService
{
    public function __construct(
        private ArticleMapper $articleMapper,
        private ArticleRepository $articleRepository,
        private Context $context,
        private FavoriteRepository $favoriteRepository,
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

    private function getContextProfile(): Profile
    {
        $user = $this->context->getUser();
        if ($user === null) {
            throw new UnauthorizedException();
        }
        return $user->getProfile();
    }

    private function verifyPermissions(Article $article): void
    {
        $profile = $this->getContextProfile();
        if ($profile->getId() !== $article->getAuthor()->getId()) {
            throw new ForbiddenException('Article delete/update is forbidden');
        }
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

    public function updateArticle(string $slug, ArticleDto $data): ArticleDto
    {
        $article = $this->findArticle($slug);
        $this->verifyPermissions($article);
        $this->save($data, $article);
        return $this->toDto($article);
    }

    public function deleteArticle(string $slug): void
    {
        $article = $this->findArticle($slug);
        $this->verifyPermissions($article);
        $this->articleRepository->remove($article);
    }

    public function favoriteArticle(string $slug): ArticleDto
    {
        $article = $this->findArticle($slug);
        $profile = $this->getContextProfile();
        $this->favoriteRepository->add($article, $profile);
        return $this->toDto($article);
    }

    public function unfavoriteArticle(string $slug): ArticleDto
    {
        $article = $this->findArticle($slug);
        $profile = $this->getContextProfile();
        $this->favoriteRepository->remove($article, $profile);
        return $this->toDto($article);
    }

    public function countArticlesFeed(): int
    {
        $profile = $this->getContextProfile();
        return $this->articleRepository->countArticlesFeed($profile->getId());
    }

    public function getArticlesFeed(int $limit, int $offset): array
    {
        $profile = $this->getContextProfile();
        $articles = $this->articleRepository->findArticlesFeed($profile->getId(), $limit, $offset);
        return array_map(fn (Article $article) => $this->toDto($article), $articles);
    }

    public function countArticles(?string $author = null, ?string $favorited = null, ?string $tag = null): int
    {
        return $this->articleRepository->countArticles($author, $favorited, $tag);
    }

    public function getArticles(
        int $limit,
        int $offset,
        ?string $author = null,
        ?string $favorited = null,
        ?string $tag = null
    ): array {
        $articles = $this->articleRepository->findArticles($limit, $offset, $author, $favorited, $tag);
        return array_map(fn (Article $article) => $this->toDto($article), $articles);
    }
}
