<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\UserDto;
use App\Entity\User;

class UserMapper
{
    public function mapDtoToEntity(UserDto $dto, ?User $entity = null): User
    {
        $result = $entity ?: new User();
        if ($dto->email !== null) {
            $result->setEmail($dto->email);
        }
        return $result;
    }

    public function mapEntityToDto(User $entity): UserDto
    {
        $result = new UserDto();
        $result->email = $entity->getEmail();
        return $result;
    }
}
