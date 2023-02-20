<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testId(): void
    {
        $tag = new Tag();
        $this->assertNull($tag->getId());

        $this->assertFalse(method_exists($tag, 'setId'));
    }

    public function testCreatedAt(): void
    {
        $tag = new Tag();
        $this->assertNull($tag->getCreatedAt());

        $createdAt = date_create_immutable();
        $tag->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $tag->getCreatedAt());
    }
}
