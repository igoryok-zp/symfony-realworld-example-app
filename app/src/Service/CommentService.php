<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CommentDto;
use App\Entity\Article;
use App\Entity\Comment;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Mapper\CommentMapper;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Utility\Context;

class CommentService
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private CommentMapper $commentMapper,
        private CommentRepository $commentRepository,
        private Context $context,
    ) {
    }

    private function toDto(Comment $comment): CommentDto
    {
        return $this->commentMapper->mapEntityToDto($comment);
    }

    private function findArticle(string $slug): Article
    {
        $article = $this->articleRepository->findOneBySlug($slug);
        if ($article === null) {
            throw new NotFoundException('Article "' . $slug . '" does not exist');
        }
        return $article;
    }

    private function save(Article $article, CommentDto $data): Comment
    {
        $result = $this->commentMapper->mapDtoToEntity($data);
        $result->setArticle($article);
        $this->commentRepository->save($result);
        return $result;
    }

    private function verifyPermissions(Comment $comment): void
    {
        $profile = $this->context->getProfile();
        if ($profile->getId() !== $comment->getAuthor()?->getId()) {
            throw new ForbiddenException('Comment deletion is forbidden');
        }
    }

    /**
     * @param string $slug
     * @return CommentDto[]
     */
    public function getArticleComments(string $slug): array
    {
        $article = $this->findArticle($slug);
        $comments = $this->commentRepository->findBy(['article' => $article], ['id' => 'ASC']);
        return array_map(fn ($comment) => $this->toDto($comment), $comments);
    }

    public function createArticleComment(string $slug, CommentDto $data): CommentDto
    {
        $article = $this->findArticle($slug);
        $comment = $this->save($article, $data);
        return $this->toDto($comment);
    }

    public function deleteArticleComment(string $slug, int $commentId): void
    {
        $article = $this->findArticle($slug);
        $comment = $this->commentRepository->find($commentId);
        if ($comment === null || $comment->getArticle()?->getId() !== $article->getId()) {
            throw new NotFoundException('Comment does not exist');
        }
        $this->verifyPermissions($comment);
        $this->commentRepository->remove($comment);
    }
}
