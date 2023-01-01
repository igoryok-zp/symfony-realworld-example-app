<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Mapper\ProfileMapper;
use App\Repository\FollowerRepository;
use App\Repository\ProfileRepository;
use App\Service\ProfileService;

class ProfileServiceTest extends ServiceTestCase
{
    private function createService(?int $contextUserId = null): ProfileService
    {
        return new ProfileService(
            $this->createContext($contextUserId),
            $this->buildProxy(FollowerRepository::class),
            $this->buildProxy(ProfileMapper::class),
            $this->buildProxy(ProfileRepository::class),
        );
    }

    public function testFollowProfileNotFound()
    {
        $this->expectException(NotFoundException::class);

        $service = $this->createService(1);
        $service->followProfile('test');
    }

    public function testFollowProfileUnauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $service = $this->createService();
        $service->followProfile('user1');
    }

    public function testFollowProfileForbidden()
    {
        $this->expectException(ForbiddenException::class);

        $service = $this->createService(1);
        $service->followProfile('user1');
    }
}
