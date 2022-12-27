<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\ProfileDto;
use App\Entity\Profile;

class ProfileMapper
{
    public function mapEntityToDto(Profile $entity): ProfileDto
    {
        $result = new ProfileDto();
        $result->username = $entity->getUsername();
        $result->bio = $entity->getBio();
        $result->image = $entity->getImage();
        return $result;
    }
}
