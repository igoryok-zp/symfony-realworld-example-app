<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Mapper\ArticleMapper;
use App\Repository\ArticleRepository;
use App\Repository\FavoriteRepository;
use App\Service\ArticleService;

class ArticleServiceTest extends ServiceTestCase
{
    private function createService(?int $contextUserId = null): ArticleService
    {
        return new ArticleService(
            $this->buildProxy(ArticleMapper::class),
            $this->buildProxy(ArticleRepository::class),
            $this->createContext($contextUserId),
            $this->buildProxy(FavoriteRepository::class),
        );
    }

    public function testDeleteArticleForbidden()
    {
        $this->expectException(ForbiddenException::class);

        $service = $this->createService(2);
        $service->deleteArticle('article-1');
    }

    public function testDeleteArticleNotFound()
    {
        $this->expectException(NotFoundException::class);

        $service = $this->createService(1);
        $service->deleteArticle('test');
    }

    public function testDeleteUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $service = $this->createService();
        $service->deleteArticle('article-1');
    }
}
