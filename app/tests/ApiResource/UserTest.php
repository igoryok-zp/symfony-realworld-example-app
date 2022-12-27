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

    private function requestUsers(string $method, string $api, array $data = []): array
    {
        $userData = [];
        if (!empty($data)) {
            $userData['user'] = $data;
        }
        $result = $this->requestApi($method, $api, $userData);
        return $result['user'] ?? [];
    }

    public function testCreate()
    {
        $email = 'test@app.test';
        $password = 'test1';

        $user = $this->requestUsers('POST', 'users', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertMatchesUserJsonSchema('create');

        $this->assertEquals($email, $user['email']);
        $this->assertFalse(isset($user['password']));
        $this->assertNotEmpty($user['token']);
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
    }
}
