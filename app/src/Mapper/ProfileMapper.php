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
        $key = $profile->getId();
        $result = $this->followings[$key] ?? null;
        $follower = $this->context->getProfileSafe();
        if ($result === null && $follower !== null) {
            $result = $this->followerRepository->exists($profile, $follower);
            $this->followings[$key] = $result;
        }
        return $result ?? false;
    }

    /**
     * @param Profile[] $profiles
     * @return void
     */
    private function loadFollowings(array $profiles): void
    {
        $follower = $this->context->getProfileSafe();
        if ($follower !== null) {
            $loadProfiles = [];
            foreach ($profiles as $profile) {
                $key = $profile->getId();
                if (!isset($this->followings[$key])) {
                    $this->followings[$key] = false;
                    $loadProfiles[] = $profile;
                }
            }
            if (!empty($loadProfiles)) {
                $followings = $this->followerRepository->findFollowings($follower, $loadProfiles);
                foreach ($followings as $following) {
                    $this->followings[$following->getId()] = true;
                }
            }
        }
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

    /**
     * @param Profile[] $entities
     * @return ProfileDto[]
     */
    public function mapEntitiesToDto(array $entities): array
    {
        $this->loadFollowings($entities);
        return array_map(fn (Profile $entity) => $this->mapEntityToDto($entity), $entities);
    }
}
