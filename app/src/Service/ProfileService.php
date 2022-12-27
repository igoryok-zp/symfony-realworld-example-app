<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ProfileDto;
use App\Entity\Profile;
use App\Exception\NotFoundException;
use App\Mapper\ProfileMapper;
use App\Repository\ProfileRepository;

class ProfileService
{
    public function __construct(
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

    public function getProfile(string $username): ?ProfileDto
    {
        $result = null;
        $profile = $this->findProfile($username, true);
        if ($profile !== null) {
            $result = $this->toDto($profile);
        }
        return $result;
    }
}
