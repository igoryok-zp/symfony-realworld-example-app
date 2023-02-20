<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testCreatedAt(): void
    {
        $comment = new Comment();
        $this->assertNull($comment->getCreatedAt());

        $createdAt = date_create_immutable();
        $comment->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $comment->getCreatedAt());
    }

    public function testUpdatedAt(): void
    {
        $comment = new Comment();
        $this->assertNull($comment->getUpdatedAt());

        $updatedAt = date_create_immutable();
        $comment->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $comment->getUpdatedAt());
    }
}
