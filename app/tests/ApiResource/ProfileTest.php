<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use App\ApiResource\Profile;

class ProfileTest extends ApiResourceTestCase
{
    protected static function assertMatchesProfileJsonSchema(string $operationName): void
    {
        static::assertMatchesApiResourceJsonSchema(Profile::class, 'profile_' . $operationName);
    }

    /**
     * @param string $method
     * @param string $profileApi
     * @param string $token
     * @return mixed[]
     */
    protected static function requestProfiles(string $method, string $profileApi, string $token = ''): array
    {
        /** @var mixed[][] */
        $result = static::requestApi($method, 'profiles/' . $profileApi, token: $token);
        return $result['profile'] ?? [];
    }

    protected static function assertFollowing(string $token, string $username, bool $expected): void
    {
        $profile = static::requestProfiles('GET', $username, $token);

        static::assertMatchesProfileJsonSchema('get');

        static::assertEquals($username, $profile['username']);
        static::assertEquals($expected, $profile['following']);
    }

    public function testGet(): void
    {
        $username = 'user1';

        $profile = $this->requestProfiles('GET', $username);

        $this->assertMatchesProfileJsonSchema('get');

        $this->assertEquals($username, $profile['username']);
        $this->assertEquals('bio 1', $profile['bio']);
        $this->assertEquals('image-1.png', $profile['image']);
        $this->assertEquals(false, $profile['following']);
    }

    public function testFollow(): void
    {
        $username = 'user2';

        $token = $this->getToken('user1@app.test', 'pswd1');

        $this->assertFollowing($token, $username, false);

        $profile = $this->requestProfiles('POST', $username . '/follow', $token);

        $this->assertMatchesProfileJsonSchema('follow');

        $this->assertEquals($username, $profile['username']);
        $this->assertEquals(true, $profile['following']);
    }

    public function testUnfollow(): void
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
