<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\ApiResource\Profile;
use App\Service\ProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ProfileFollowController extends AbstractController
{
    public function __construct(
        private ProfileService $service,
    ) {
    }

    public function __invoke(string $username, Request $request): Profile
    {
        $result = new Profile();
        $result->profile = $request->getMethod() !== 'DELETE'
            ? $this->service->followProfile($username)
            : $this->service->unfollowProfile($username);
        return $result;
    }
}
