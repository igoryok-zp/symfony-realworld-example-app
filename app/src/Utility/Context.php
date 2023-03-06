<?php

declare(strict_types=1);

namespace App\Utility;

use App\Entity\User;
use App\Entity\Profile;
use App\Exception\UnauthorizedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Context
{
    public function __construct(
        private TokenStorageInterface $tokenStogage,
    ) {
    }

    public function getUser(): User
    {
        $result = $this->getUserSafe();
        if ($result === null) {
            throw new UnauthorizedException();
        }
        return $result;
    }

    public function getUserSafe(): ?User
    {
        $result = null;
        $token = $this->tokenStogage->getToken();
        if ($token) {
            /** @var User */
            $result = $token->getUser();
        }
        return $result;
    }

    public function getProfile(): Profile
    {
        $result = $this->getProfileSafe();
        if ($result === null) {
            throw new UnauthorizedException();
        }
        return $result;
    }

    public function getProfileSafe(): ?Profile
    {
        return $this->getUserSafe()?->getProfile();
    }
}
