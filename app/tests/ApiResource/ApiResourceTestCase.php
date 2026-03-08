<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as ApiPlatformApiTestCase;

class ApiResourceTestCase extends ApiPlatformApiTestCase
{
    protected static function assertMatchesApiResourceJsonSchema(string $resourceClass, string $operationName): void
    {
        static::assertMatchesResourceItemJsonSchema($resourceClass, $operationName, 'json');
    }

    /**
     * @param string $method
     * @param string $api
     * @param mixed[] $data
     * @param string $token
     * @return mixed[]
     */
    protected static function requestApi(string $method, string $api, array $data = [], string $token = ''): array
    {
        $url = '/api/' . $api;
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        if (!empty($token)) {
            $headers['Authorization'] = 'Token ' . $token;
        }
        $options = ['headers' => $headers];
        if (!empty($data)) {
            $options['json'] = $data;
        }

        $response = static::createClient()->request($method, $url, $options);
        static::assertResponseIsSuccessful();

        $result = (array) json_decode($response->getContent(false), true);
        return $result;
    }

    protected static function getToken(string $email, string $password): string
    {
        /** @var string[][] */
        $data = static::requestApi('POST', 'users/login', [
            'user' => [
                'email' => $email,
                'password' => $password,
            ]
        ]);
        return $data['user']['token'];
    }
}
