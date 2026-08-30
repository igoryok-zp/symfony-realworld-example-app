<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\ArticleDto;
use App\Dto\ProfileDto;
use App\Entity\Article;
use App\Entity\Tag;
use App\Repository\FavoriteRepository;
use App\Repository\TagRepository;
use App\Utility\Context;

class ArticleMapper
{
    /**
     * @var bool[]
     */
    private $favoriteIds = [];

    /**
     * @var int[]
     */
    private $favoriteCounts = [];

    /**
     * @var ProfileDto[]
     */
    private $authors = [];

    public function __construct(
        private Context $context,
        private FavoriteRepository $favoriteRepository,
        private ProfileMapper $profileMapper,
        private TagMapper $tagMapper,
        private TagRepository $tagRepository,
    ) {
    }

    private function isFavorited(Article $article): bool
    {
        $result = $this->favoriteIds[$article->getId()] ?? null;
        $profile = $this->context->getProfileSafe();
        if ($result === null && $profile !== null) {
            $result = $this->favoriteRepository->exists($article, $profile);
        }
        return $result ?? false;
    }

    private function getFavoritesCount(Article $article): int
    {
        $result = $this->favoriteCounts[$article->getId()] ?? null;
        if ($result === null) {
            $result = $this->favoriteRepository->countByArticle($article);
        }
        return $result;
    }

    private function getAuthor(Article $article): ?ProfileDto
    {
        $result = null;
        $author = $article->getAuthor();
        if ($author !== null) {
            $result = $this->authors[$author->getUsername()] ?? $this->profileMapper->mapEntityToDto($author);
        }
        return $result;
    }

    /**
     * @param Article[] $articles
     * @return void
     */
    private function loadFavoriteIds(array $articles): void
    {
        $profile = $this->context->getProfileSafe();
        if ($profile !== null) {
            $loadArticles = [];
            /** @var Article $article */
            foreach ($articles as $article) {
                if (!isset($this->favoriteIds[$article->getId()])) {
                    $this->favoriteIds[$article->getId()] = false;
                    $loadArticles[] = $article;
                }
            }
            if (!empty($loadArticles)) {
                $favorites = $this->favoriteRepository->findByProfile($profile, $loadArticles);
                foreach ($favorites as $favorite) {
                    if ($favorite->getArticle()) {
                        $this->favoriteIds[$favorite->getArticle()->getId()] = true;
                    }
                }
            }
        }
    }

    /**
     * @param Article[] $articles
     * @return void
     */
    private function loadFavoriteCounts(array $articles): void
    {
        $loadArticles = [];
        foreach ($articles as $article) {
            if (!isset($this->favoriteCounts[$article->getId()])) {
                $this->favoriteCounts[$article->getId()] = 0;
                $loadArticles[] = $article;
            }
        }
        if (!empty($loadArticles)) {
            $favoriteCounts = $this->favoriteRepository->countByArticles($loadArticles);
            foreach ($favoriteCounts as $articleId => $favoriteCount) {
                $this->favoriteCounts[$articleId] = $favoriteCount;
            }
        }
    }

    /**
     * @param Article[] $articles
     * @return void
     */
    private function loadAuthors(array $articles): void
    {
        $loadAuthors = [];
        foreach ($articles as $article) {
            $author = $article->getAuthor();
            if (
                $author !== null &&
                !isset($this->authors[$author->getUsername()]) &&
                !isset($loadAuthors[$author->getUsername()])
            ) {
                $loadAuthors[$author->getUsername()] = $author;
            }
        }
        if (!empty($loadAuthors)) {
            $authors = $this->profileMapper->mapEntitiesToDto(array_values($loadAuthors));
            foreach ($authors as $author) {
                $this->authors[$author->username] = $author;
            }
        }
    }

    /**
     * @param Article $article
     * @param string[] $tags
     * @return void
     */
    private function setTags(Article $article, array $tags): void
    {
        $oldTags = $article->getTags()->map(fn (Tag $tag) => $tag->getName())->getValues();
        $delTags = array_diff($oldTags, $tags);
        if (!empty($delTags)) {
            foreach ($article->getTags()->filter(fn (Tag $tag) => in_array($tag->getName(), $delTags)) as $tag) {
                $article->removeTag($tag);
            }
        }
        $addTags = array_diff($tags, $oldTags);
        if (!empty($addTags)) {
            foreach ($this->tagRepository->findOrCreate($addTags) as $tag) {
                $article->addTag($tag);
            }
        }
    }

    public function mapDtoToEntity(ArticleDto $dto, ?Article $entity = null): Article
    {
        $result = $entity ?: new Article();
        if ($dto->title !== null) {
            $result->setTitle($dto->title);
        }
        if ($dto->description !== null) {
            $result->setDescription($dto->description);
        }
        if ($dto->body !== null) {
            $result->setBody($dto->body);
        }
        if ($dto->tagList !== null) {
            $this->setTags($result, $dto->tagList);
        }
        if ($result->getAuthor() === null && $this->context->getProfileSafe() !== null) {
            $result->setAuthor($this->context->getProfileSafe());
        }
        return $result;
    }

    public function mapEntityToDto(Article $entity): ArticleDto
    {
        $result = new ArticleDto();
        $result->slug = $entity->getSlug();
        $result->title = $entity->getTitle();
        $result->description = $entity->getDescription();
        $result->body = $entity->getBody();
        $result->tagList = $this->tagMapper->mapEntitiesToStringArray($entity->getTags());
        $result->createdAt = $entity->getCreatedAt();
        $result->updatedAt = $entity->getCreatedAt();
        $result->favorited = $this->isFavorited($entity);
        $result->favoritesCount = $this->getFavoritesCount($entity);
        $result->author = $this->getAuthor($entity);
        return $result;
    }

    /**
     * @param Article[] $entities
     * @return ArticleDto[]
     */
    public function mapEntitiesToDtos(array $entities): array
    {
        $this->loadFavoriteIds($entities);
        $this->loadFavoriteCounts($entities);
        $this->loadAuthors($entities);
        $this->tagRepository->loadTags($entities);
        return array_map(fn(Article $entity) => $this->mapEntityToDto($entity), $entities);
    }
}
