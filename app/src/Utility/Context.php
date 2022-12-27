<?php

declare(strict_types=1);

namespace App\Utility;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Context
{
    public function __construct(
        private TokenStorageInterface $tokenStogage,
    ) {
    }

    public function getUser(): ?User
    {
        $result = null;
        $token = $this->tokenStogage->getToken();
        if ($token) {
            /** @var User */
            $result = $token->getUser();
        }
        return $result;
    }
}
