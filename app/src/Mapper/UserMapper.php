<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\UserDto;
use App\Entity\Profile;
use App\Entity\User;

class UserMapper
{
    public function mapDtoToEntity(UserDto $dto, ?User $entity = null): User
    {
        $result = $entity ?: new User();
        if ($dto->email !== null) {
            $result->setEmail($dto->email);
        }
        $profile = $result->getProfile();
        if ($profile === null) {
            $profile = new Profile();
            $result->setProfile($profile);
        }
        if ($dto->username !== null) {
            $profile->setUsername($dto->username);
        }
        if ($dto->bio !== null) {
            $profile->setBio($dto->bio);
        }
        if ($dto->image !== null) {
            $profile->setImage($dto->image !== '' ? $dto->image : null);
        }
        return $result;
    }

    public function mapEntityToDto(User $entity): UserDto
    {
        $result = new UserDto();
        $result->email = $entity->getEmail();
        $result->username = $entity->getProfile()->getUsername();
        $result->bio = $entity->getProfile()->getBio();
        $result->image = $entity->getProfile()->getImage();
        return $result;
    }
}
