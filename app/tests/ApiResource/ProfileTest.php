<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use App\ApiResource\Profile;

class ProfileTest extends ApiResourceTestCase
{
    private function assertMatchesProfileJsonSchema(string $operationName)
    {
        $this->assertMatchesApiResourceJsonSchema(Profile::class, 'profile_' . $operationName);
    }

    private function requestProfiles(string $method, string $profileApi, string $token = ''): array
    {
        $result = $this->requestApi($method, 'profiles/' . $profileApi, token: $token);
        return $result['profile'] ?? [];
    }

    public function testGet()
    {
        $username = 'user1';

        $profile = $this->requestProfiles('GET', $username);

        $this->assertMatchesProfileJsonSchema('get');

        $this->assertEquals($username, $profile['username']);
        $this->assertEquals('bio 1', $profile['bio']);
        $this->assertEquals('image-1.png', $profile['image']);
    }
}
