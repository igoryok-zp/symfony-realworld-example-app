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
    private $followerRepository;
    private $profileRepository;

    private function createService(?int $contextUserId = null): ProfileService
    {
        $this->followerRepository = $this->buildProxy(FollowerRepository::class);
        $this->profileRepository = $this->buildProxy(ProfileRepository::class);

        return new ProfileService(
            $this->createContext($contextUserId),
            $this->followerRepository,
            $this->getService(ProfileMapper::class),
            $this->profileRepository,
        );
    }

    public function followProfileExceptionDataProvider()
    {
        return [[
            'test',
            NotFoundException::class,
            1
        ], [
            'user1',
            UnauthorizedException::class,
        ], [
            'user1',
            ForbiddenException::class,
            1,
        ]];
    }

    /**
     * @dataProvider followProfileExceptionDataProvider
     */
    public function testFollowProfileException(string $username, string $exception, ?int $contextUserId = null)
    {
        $this->expectException($exception);

        $service = $this->createService($contextUserId);

        $this->profileRepository
            ->expects($this->once())
            ->method('__call')
            ->with('findOneByUsername', [$username]);

        $this->followerRepository
            ->expects($this->never())
            ->method('add');

        $service->followProfile($username);
    }
}
