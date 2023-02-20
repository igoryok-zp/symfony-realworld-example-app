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
use PHPUnit\Framework\MockObject\MockObject;

class ArticleServiceTest extends ServiceTestCase
{
    /**
     * @var MockObject&ArticleRepository
     */
    private $articleRepository;

    private function createService(?int $contextUserId = null): ArticleService
    {
        $this->articleRepository = $this->buildProxy(ArticleRepository::class);

        return new ArticleService(
            $this->getService(ArticleMapper::class),
            $this->articleRepository,
            $this->createContext($contextUserId),
            $this->getService(FavoriteRepository::class),
        );
    }

    /**
     * @return mixed[]
     */
    public function deleteArticleExceptionDataProvider(): array
    {
        return [[
            'article-1',
            ForbiddenException::class,
            2,
        ], [
            'test',
            NotFoundException::class,
            1,
        ], [
            'article-1',
            UnauthorizedException::class,
        ]];
    }

    /**
     * @dataProvider deleteArticleExceptionDataProvider
     */
    public function testDeleteArticleException(string $slug, string $exception, ?int $contextUserId = null): void
    {
        $this->expectException($exception);

        $service = $this->createService($contextUserId);

        $this->articleRepository
            ->expects($this->once())
            ->method('__call')
            ->with('findOneBySlug', [$slug]);

        $this->articleRepository
            ->expects($this->never())
            ->method('remove');

        $service->deleteArticle($slug);
    }
}
