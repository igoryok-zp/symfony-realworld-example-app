<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\ArticleDto;
use App\Entity\Article;
use App\Entity\Tag;
use App\Repository\FavoriteRepository;
use App\Repository\TagRepository;
use App\Utility\Context;

class ArticleMapper
{
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
        $result = false;
        $profile = $this->context->getProfileSafe();
        if ($profile !== null) {
            $result = $this->favoriteRepository->exists($article, $profile);
        }
        return $result;
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
        $result->favoritesCount = $this->favoriteRepository->countByArticle($entity);
        if ($entity->getAuthor() !== null) {
            $result->author = $this->profileMapper->mapEntityToDto($entity->getAuthor());
        }
        return $result;
    }
}
