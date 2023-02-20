<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\ProfileDto;
use App\Entity\Profile;
use App\Repository\FollowerRepository;
use App\Utility\Context;

class ProfileMapper
{
    /**
     * @var bool[]
     */
    private $followings = [];

    public function __construct(
        private Context $context,
        private FollowerRepository $followerRepository,
    ) {
    }

    private function isFollowing(Profile $profile): bool
    {
        $result = false;
        $user = $this->context->getUser();
        if ($user !== null) {
            $follower = $user->getProfile();
            $cacheKey = $profile->getId() . '_' . $follower->getId();
            if (!isset($this->followings[$cacheKey])) {
                $this->followings[$cacheKey] = $this->followerRepository->exists($profile, $follower);
            }
            $result = $this->followings[$cacheKey];
        }
        return $result;
    }

    public function mapEntityToDto(Profile $entity): ProfileDto
    {
        $result = new ProfileDto();
        $result->username = $entity->getUsername();
        $result->bio = $entity->getBio();
        $result->image = $entity->getImage();
        $result->following = $this->isFollowing($entity);
        return $result;
    }
}
