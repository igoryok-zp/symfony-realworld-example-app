<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as ApiPlatformApiTestCase;

class ApiResourceTestCase extends ApiPlatformApiTestCase
{
    protected function assertMatchesApiResourceJsonSchema(string $resourceClass, string $operationName)
    {
        $this->assertMatchesResourceItemJsonSchema($resourceClass, $operationName, 'json');
    }

    protected function requestApi(string $method, string $api, array $data = []): array
    {
        $url = '/api/' . $api;
        $headers = [
            'Accept' => 'application/json',
        ];
        $options = ['headers' => $headers];
        if (!empty($data)) {
            $options['json'] = $data;
        }

        $response = static::createClient()->request($method, $url, $options);
        $this->assertResponseIsSuccessful();

        $result = (array) json_decode($response->getContent(false), true);
        return $result;
    }
}
