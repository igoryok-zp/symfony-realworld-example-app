<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as ApiPlatformApiTestCase;

class ApiResourceTestCase extends ApiPlatformApiTestCase
{
    protected function assertMatchesApiResourceJsonSchema(string $resourceClass, string $operationName): void
    {
        $this->assertMatchesResourceItemJsonSchema($resourceClass, $operationName, 'json');
    }

    /**
     * @param string $method
     * @param string $api
     * @param mixed[] $data
     * @param string $token
     * @return mixed[]
     */
    protected function requestApi(string $method, string $api, array $data = [], string $token = ''): array
    {
        $url = '/api/' . $api;
        $headers = [
            'Accept' => 'application/json',
        ];
        if (!empty($token)) {
            $headers['Authorization'] = 'Token ' . $token;
        }
        $options = ['headers' => $headers];
        if (!empty($data)) {
            $options['json'] = $data;
        }

        $response = static::createClient()->request($method, $url, $options);
        $this->assertResponseIsSuccessful();

        $result = (array) json_decode($response->getContent(false), true);
        return $result;
    }

    protected function getToken(string $email, string $password): string
    {
        $data = $this->requestApi('POST', 'users/login', [
            'user' => [
                'email' => $email,
                'password' => $password,
            ]
        ]);
        return $data['user']['token'];
    }
}
