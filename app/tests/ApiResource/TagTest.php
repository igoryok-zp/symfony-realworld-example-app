<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use App\ApiResource\Tag;

class TagTest extends ApiResourceTestCase
{
    private function assertMatchesTagJsonSchema(string $operationName)
    {
        $this->assertMatchesApiResourceJsonSchema(Tag::class, 'tag_' . $operationName);
    }

    public function testGet()
    {
        $response = $this->requestApi('GET', 'tags');

        $this->assertMatchesTagJsonSchema('list');

        $tags = $response['tags'];

        $this->assertIsArray($tags);

        $expectedCount = 9;
        $this->assertCount($expectedCount, $tags);

        for ($i = 0; $i < $expectedCount; $i++) {
            $num = $i + 1;
            $this->assertEquals('tag' . $num, $tags[$i]);
        }
    }
}
