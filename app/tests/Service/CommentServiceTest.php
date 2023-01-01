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
    private function createService(?int $contextUserId = null): CommentService
    {
        return new CommentService(
            $this->buildProxy(ArticleRepository::class),
            $this->buildProxy(CommentMapper::class),
            $this->buildProxy(CommentRepository::class),
            $this->createContext($contextUserId),
        );
    }

    public function testGetArticleCommentsNotFound()
    {
        $this->expectException(NotFoundException::class);

        $service = $this->createService();
        $service->getArticleComments('test');
    }

    public function testDeleteArticleCommentNotFound()
    {
        $this->expectException(NotFoundException::class);

        $service = $this->createService(2);
        $service->deleteArticleComment('article-2', 1);
    }

    public function testDeleteArticleCommentUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $service = $this->createService();
        $service->deleteArticleComment('article-1', 1);
    }

    public function testDeleteArticleCommentForbidden()
    {
        $this->expectException(ForbiddenException::class);

        $service = $this->createService(1);
        $service->deleteArticleComment('article-1', 1);
    }
}
