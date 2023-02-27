<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Profile;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    public function testCreatedAt(): void
    {
        $profile = new Profile();
        $this->assertNull($profile->getCreatedAt());

        $createdAt = new DateTimeImmutable();
        $profile->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $profile->getCreatedAt());
    }

    public function testUpdatedAt(): void
    {
        $profile = new Profile();
        $this->assertNull($profile->getUpdatedAt());

        $updatedAt = new DateTimeImmutable();
        $profile->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $profile->getUpdatedAt());
    }
}
