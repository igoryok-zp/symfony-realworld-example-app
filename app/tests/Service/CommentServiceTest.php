<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Mapper\CommentMapper;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Service\CommentService;

class CommentServiceTest extends ServiceTestCase
{
    private $articleRepository;
    private $commentRepository;

    private function createService(?int $contextUserId = null): CommentService
    {
        $this->articleRepository = $this->buildProxy(ArticleRepository::class);
        $this->commentRepository = $this->buildProxy(CommentRepository::class);

        return new CommentService(
            $this->articleRepository,
            $this->getService(CommentMapper::class),
            $this->commentRepository,
            $this->createContext($contextUserId),
        );
    }

    private function expectArticleRepositoryFindOneBySlugOnce(string $slug)
    {
        $this->articleRepository
            ->expects($this->once())
            ->method('__call')
            ->with('findOneBySlug', [$slug]);
    }

    public function testGetArticleCommentsNotFound()
    {
        $this->expectException(NotFoundException::class);

        $service = $this->createService();

        $slug = 'test';

        $this->expectArticleRepositoryFindOneBySlugOnce($slug);

        $this->commentRepository
            ->expects($this->never())
            ->method('findBy');

        $service->getArticleComments($slug);
    }

    public function deleteArticleCommentExceptionDataProvider()
    {
        return [[
            'article-2',
            1,
            NotFoundException::class,
            2,
        ], [
            'article-1',
            1,
            UnauthorizedException::class,
        ], [
            'article-1',
            1,
            ForbiddenException::class,
            1,
        ]];
    }

    /**
     * @dataProvider deleteArticleCommentExceptionDataProvider
     */
    public function testDeleteArticleCommentException(string $slug, int $commentId, string $exception, ?int $contextUserId = null)
    {
        $this->expectException($exception);

        $service = $this->createService($contextUserId);

        $this->expectArticleRepositoryFindOneBySlugOnce($slug);

        $this->commentRepository
            ->expects($this->once())
            ->method('find')
            ->with($commentId);

        $this->commentRepository
            ->expects($this->never())
            ->method('remove');

        $service->deleteArticleComment($slug, $commentId);
    }
}
