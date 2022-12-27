<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CommentDto;
use App\Entity\Article;
use App\Entity\Comment;
use App\Exception\NotFoundException;
use App\Mapper\CommentMapper;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;

class CommentService
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private CommentMapper $commentMapper,
        private CommentRepository $commentRepository,
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

    public function getArticleComments(string $slug): array
    {
        $article = $this->findArticle($slug);
        $comments = $this->commentRepository->findBy(['article' => $article]);
        return array_map(fn ($comment) => $this->toDto($comment), $comments);
    }
}
