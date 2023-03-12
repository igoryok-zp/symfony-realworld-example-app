<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use App\ApiResource\Comment;

class CommentTest extends ApiResourceTestCase
{
    private function assertMatchesCommentJsonSchema(string $operationName): void
    {
        $this->assertMatchesApiResourceJsonSchema(Comment::class, 'comment_' . $operationName);
    }

    /**
     * @param string $method
     * @param string $slug
     * @param integer|null $commentId
     * @param mixed[] $data
     * @param string $token
     * @return mixed[]
     */
    private function requestComments(
        string $method,
        string $slug,
        ?int $commentId = null,
        array $data = [],
        string $token = ''
    ): array {
        $commentData = [];
        if (!empty($data)) {
            $commentData['comment'] = $data;
        }
        $commentApi = 'articles/' . $slug . '/comments';
        if (!empty($commentId)) {
            $commentApi .= '/' . $commentId;
        }
        $result = $this->requestApi($method, $commentApi, $commentData, $token);
        return $result['comments'] ?? $result['comment'] ?? [];
    }

    public function testList(): void
    {
        /** @var string[][][] */
        $comments = $this->requestComments('GET', 'article-1');

        $this->assertMatchesCommentJsonSchema('list');

        $expectedCount = 8;
        $this->assertCount($expectedCount, $comments);

        for ($i = 0; $i < $expectedCount; $i++) {
            $num = $i + 2;
            $this->assertEquals('Comment ' . $num, $comments[$i]['body']);
            $this->assertEquals('user' . $num, $comments[$i]['author']['username']);
        }
    }

    public function testCreate(): void
    {
        $token = $this->getToken('user1@app.test', 'pswd1');

        $body = 'Test';

        /** @var string[][] */
        $comment = $this->requestComments('POST', 'article-1', token: $token, data: [
            'body' => $body,
        ]);

        $this->assertMatchesCommentJsonSchema('create');

        $this->assertEquals($body, $comment['body']);
        $this->assertEquals('user1', $comment['author']['username']);
    }

    public function testDelete(): void
    {
        $token = $this->getToken('user2@app.test', 'pswd2');

        $this->requestComments('DELETE', 'article-1', 1, token: $token);
    }
}
