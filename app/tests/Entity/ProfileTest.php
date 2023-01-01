<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Profile;
use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    public function testCreatedAt()
    {
        $profile = new Profile();
        $this->assertNull($profile->getCreatedAt());

        $createdAt = date_create_immutable();
        $profile->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $profile->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $profile = new Profile();
        $this->assertNull($profile->getUpdatedAt());

        $updatedAt = date_create_immutable();
        $profile->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $profile->getUpdatedAt());
    }
}
