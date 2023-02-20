<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testId(): void
    {
        $user = new User();
        $this->assertNull($user->getId());

        $this->assertFalse(method_exists($user, 'setId'));
    }

    public function testRoles(): void
    {
        $user = new User();
        $roleUser = 'ROLE_USER';
        $this->assertEquals([$roleUser], $user->getRoles());

        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertEquals([...$roles, $roleUser], $user->getRoles());
    }

    public function testCreatedAt(): void
    {
        $user = new User();
        $this->assertNull($user->getCreatedAt());

        $createdAt = date_create_immutable();
        $user->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testUpdatedAt(): void
    {
        $user = new User();
        $this->assertNull($user->getUpdatedAt());

        $updatedAt = date_create_immutable();
        $user->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }
}
