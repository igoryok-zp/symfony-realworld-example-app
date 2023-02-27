<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Article;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testSlug(): void
    {
        $article = new Article();
        $this->assertNull($article->getSlug());

        $slug = 'test';
        $article->setSlug($slug);
        $this->assertEquals($slug, $article->getSlug());
    }

    public function testCreatedAt(): void
    {
        $article = new Article();
        $this->assertNull($article->getCreatedAt());

        $createdAt = new DateTimeImmutable();
        $article->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $article->getCreatedAt());
    }

    public function testUpdatedAt(): void
    {
        $article = new Article();
        $this->assertNull($article->getUpdatedAt());

        $updatedAt = new DateTimeImmutable();
        $article->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $article->getUpdatedAt());
    }
}
