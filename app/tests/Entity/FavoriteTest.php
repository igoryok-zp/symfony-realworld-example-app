<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Article;
use App\Entity\Favorite;
use App\Entity\Profile;
use PHPUnit\Framework\TestCase;

class FavoriteTest extends TestCase
{
    public function testArticle()
    {
        $favorite = new Favorite();
        $this->assertNull($favorite->getArticle());

        $article = new Article();
        $favorite->setArticle($article);
        $this->assertEquals($article, $favorite->getArticle());
    }

    public function testProfile()
    {
        $favorite = new Favorite();
        $this->assertNull($favorite->getProfile());

        $profile = new Profile();
        $favorite->setProfile($profile);
        $this->assertEquals($profile, $favorite->getProfile());
    }

    public function testCreatedAt()
    {
        $favorite = new Favorite();
        $this->assertNull($favorite->getCreatedAt());

        $createdAt = date_create_immutable();
        $favorite->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $favorite->getCreatedAt());
    }
}
