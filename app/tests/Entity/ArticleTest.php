<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testSlug()
    {
        $article = new Article();
        $this->assertNull($article->getSlug());

        $slug = 'test';
        $article->setSlug($slug);
        $this->assertEquals($slug, $article->getSlug());
    }

    public function testCreatedAt()
    {
        $article = new Article();
        $this->assertNull($article->getCreatedAt());

        $createdAt = date_create_immutable();
        $article->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $article->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $article = new Article();
        $this->assertNull($article->getUpdatedAt());

        $updatedAt = date_create_immutable();
        $article->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $article->getUpdatedAt());
    }
}
