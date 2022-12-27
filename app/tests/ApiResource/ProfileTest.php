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

    private function assertFollowing(string $token, string $username, bool $expected)
    {
        $profile = $this->requestProfiles('GET', $username, $token);

        $this->assertMatchesProfileJsonSchema('get');

        $this->assertEquals($username, $profile['username']);
        $this->assertEquals($expected, $profile['following']);
    }

    public function testGet()
    {
        $username = 'user1';

        $profile = $this->requestProfiles('GET', $username);

        $this->assertMatchesProfileJsonSchema('get');

        $this->assertEquals($username, $profile['username']);
        $this->assertEquals('bio 1', $profile['bio']);
        $this->assertEquals('image-1.png', $profile['image']);
        $this->assertEquals(false, $profile['following']);
    }

    public function testFollow()
    {
        $username = 'user2';

        $token = $this->getToken('user1@app.test', 'pswd1');

        $this->assertFollowing($token, $username, false);

        $profile = $this->requestProfiles('POST', $username . '/follow', $token);

        $this->assertMatchesProfileJsonSchema('follow');

        $this->assertEquals($username, $profile['username']);
        $this->assertEquals(true, $profile['following']);
    }

    public function testUnfollow()
    {
        $username = 'user1';

        $token = $this->getToken('user2@app.test', 'pswd2');

        $this->assertFollowing($token, $username, true);

        $profile = $this->requestProfiles('DELETE', $username . '/follow', $token);

        $this->assertMatchesProfileJsonSchema('unfollow');

        $this->assertEquals($username, $profile['username']);
        $this->assertEquals(false, $profile['following']);
    }
}
