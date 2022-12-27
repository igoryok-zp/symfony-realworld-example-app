<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ProfileDto;
use App\Entity\Profile;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Mapper\ProfileMapper;
use App\Repository\FollowerRepository;
use App\Repository\ProfileRepository;
use App\Utility\Context;

class ProfileService
{
    public function __construct(
        private Context $context,
        private FollowerRepository $followerRepository,
        private ProfileMapper $profileMapper,
        private ProfileRepository $profileRepository,
    ) {
    }

    private function toDto(Profile $profile): ProfileDto
    {
        return $this->profileMapper->mapEntityToDto($profile);
    }

    /** @SuppressWarnings(PHPMD.BooleanArgumentFlag) */
    private function findProfile(string $username, bool $safe = false): ?Profile
    {
        $profile = $this->profileRepository->findOneByUsername($username);
        if ($profile === null && !$safe) {
            throw new NotFoundException('Profile "' . $username . '" does not exist');
        }
        return $profile;
    }

    private function getFollower(): Profile
    {
        $user = $this->context->getUser();
        if ($user === null) {
            throw new UnauthorizedException();
        }
        return $user->getProfile();
    }

    public function getProfile(string $username): ?ProfileDto
    {
        $result = null;
        $profile = $this->findProfile($username, true);
        if ($profile !== null) {
            $result = $this->toDto($profile);
        }
        return $result;
    }

    public function followProfile(string $username): ProfileDto
    {
        $profile = $this->findProfile($username);
        $follower = $this->getFollower();
        if ($profile->getId() === $follower->getId()) {
            throw new ForbiddenException('Self following is not allowed');
        }
        $this->followerRepository->add($profile, $follower);
        return $this->toDto($profile);
    }

    public function unfollowProfile(string $username): ProfileDto
    {
        $profile = $this->findProfile($username);
        $follower = $this->getFollower();
        $this->followerRepository->remove($profile, $follower);
        return $this->toDto($profile);
    }
}
