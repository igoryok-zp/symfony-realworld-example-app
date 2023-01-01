<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Follower;
use App\Entity\Profile;
use PHPUnit\Framework\TestCase;

class FollowerTest extends TestCase
{
    public function testProfile()
    {
        $follower = new Follower();
        $this->assertNull($follower->getProfile());

        $profile = new Profile();
        $follower->setProfile($profile);
        $this->assertEquals($profile, $follower->getProfile());
    }

    public function testFollower()
    {
        $follower = new Follower();
        $this->assertNull($follower->getFollower());

        $profile = new Profile();
        $follower->setFollower($profile);
        $this->assertEquals($profile, $follower->getFollower());
    }

    public function testCreatedAt()
    {
        $follower = new Follower();
        $this->assertNull($follower->getCreatedAt());

        $createdAt = date_create_immutable();
        $follower->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $follower->getCreatedAt());
    }
}
