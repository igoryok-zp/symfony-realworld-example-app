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
use App\Utility\Context;

class ArticleService
{
    public function __construct(
        private ArticleMapper $articleMapper,
        private ArticleRepository $articleRepository,
        private Context $context,
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
}
