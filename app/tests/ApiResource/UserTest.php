<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use App\ApiResource\User;

class UserTest extends ApiResourceTestCase
{
    private function assertMatchesUserJsonSchema(string $operationName)
    {
        $this->assertMatchesApiResourceJsonSchema(User::class, 'user_' . $operationName);
    }

    private function requestUsers(string $method, string $api, array $data = [], string $token = ''): array
    {
        $userData = [];
        if (!empty($data)) {
            $userData['user'] = $data;
        }
        $result = $this->requestApi($method, $api, $userData, $token);
        return $result['user'] ?? [];
    }

    public function testCreate()
    {
        $email = 'test@app.test';
        $password = 'test1';
        $username = 'test';
        $bio = 'test bio';
        $image = 'test.png';

        $user = $this->requestUsers('POST', 'users', [
            'email' => $email,
            'password' => $password,
            'username' => $username,
            'bio' => $bio,
            'image' => $image,
        ]);

        $this->assertMatchesUserJsonSchema('create');

        $this->assertEquals($email, $user['email']);
        $this->assertFalse(isset($user['password']));
        $this->assertNotEmpty($user['token']);
        $this->assertEquals($username, $user['username']);
        $this->assertEquals($bio, $user['bio']);
        $this->assertEquals($image, $user['image']);
    }

    public function testLogin()
    {
        $email = 'user1@app.test';
        $password = 'pswd1';

        $user = $this->requestUsers('POST', 'users/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertMatchesUserJsonSchema('login');

        $this->assertEquals($email, $user['email']);
        $this->assertFalse(isset($user['password']));
        $this->assertNotEmpty($user['token']);
        $this->assertEquals('user1', $user['username']);
        $this->assertEquals('bio 1', $user['bio']);
        $this->assertEquals('image-1.png', $user['image']);
    }

    public function testCurrent()
    {
        $email = 'user1@app.test';
        $password = 'pswd1';

        $token = $this->getToken($email, $password);

        $user = $this->requestUsers('GET', 'user', token: $token);

        $this->assertMatchesUserJsonSchema('current');

        $this->assertEquals($email, $user['email']);
        $this->assertFalse(isset($user['password']));
        $this->assertNotEmpty($user['token']);
        $this->assertEquals('user1', $user['username']);
        $this->assertEquals('bio 1', $user['bio']);
        $this->assertEquals('image-1.png', $user['image']);
    }

    public function testUpdate()
    {
        $token = $this->getToken('user1@app.test', 'pswd1');

        $email = 'test@app.test';
        $username = 'test';
        $bio = 'test bio';
        $image = 'test.png';

        $user = $this->requestUsers('PUT', 'user', [
            'email' => $email,
            'username' => $username,
            'bio' => $bio,
            'image' => $image,
        ], $token);

        $this->assertMatchesUserJsonSchema('update');

        $this->assertEquals($email, $user['email']);
        $this->assertFalse(isset($user['password']));
        $this->assertNotEmpty($user['token']);
        $this->assertEquals($username, $user['username']);
        $this->assertEquals($bio, $user['bio']);
        $this->assertEquals($image, $user['image']);
    }
}
