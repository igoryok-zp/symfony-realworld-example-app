<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use App\ApiResource\Article;

class ArticleTest extends ApiResourceTestCase
{
    private function assertMatchesArticleJsonSchema(string $operationName): void
    {
        $this->assertMatchesApiResourceJsonSchema(Article::class, 'article_' . $operationName);
    }

    /**
     * @param string $method
     * @param string $api
     * @param string $query
     * @param mixed[] $data
     * @param string $token
     * @return mixed[]
     */
    private function requestArticles(
        string $method,
        string $api = '',
        string $query = '',
        array $data = [],
        string $token = ''
    ): array {
        $articleData = [];
        if (!empty($data)) {
            $articleData['article'] = $data;
        }
        $articleApi = 'articles';
        if (!empty($api)) {
            $articleApi .= '/' . $api;
        }
        if (!empty($query)) {
            $articleApi .= '?' . $query;
        }
        /** @var mixed[][] */
        $result = $this->requestApi($method, $articleApi, $articleData, $token);
        return $result['article'] ?? array_filter([
            $result['articlesCount'] ?? null,
            $result['articles'] ?? null,
        ]);
    }

    private function assertFavorited(string $token, string $slug, bool $expected): void
    {
        $article = $this->requestArticles('GET', $slug, token: $token);

        $this->assertMatchesArticleJsonSchema('get');

        $this->assertEquals($slug, $article['slug']);
        $this->assertEquals($expected, $article['favorited']);
    }

    public function testGet(): void
    {
        $slug = 'article-1';

        /** @var string[][] */
        $article = $this->requestArticles('GET', $slug);

        $this->assertMatchesArticleJsonSchema('get');

        $this->assertEquals($slug, $article['slug']);
        $this->assertEquals('Article 1', $article['title']);
        $this->assertEquals('Description 1', $article['description']);
        $this->assertEquals('Body 1', $article['body']);
        $this->assertEquals(['tag1'], $article['tagList']);
        $this->assertEquals(false, $article['favorited']);
        $this->assertEquals(8, $article['favoritesCount']);
        $this->assertEquals('user1', $article['author']['username']);
    }

    public function testCreate(): void
    {
        $token = $this->getToken('user1@app.test', 'pswd1');

        $title = 'Test';
        $description = 'Test Description';
        $body = 'Test Body';
        $tagList = ['test'];

        /** @var string[][] */
        $article = $this->requestArticles('POST', token: $token, data: [
            'title' => $title,
            'description' => $description,
            'body' => $body,
            'tagList' => $tagList,
        ]);

        $this->assertMatchesArticleJsonSchema('create');

        $this->assertEquals('test', $article['slug']);
        $this->assertEquals($title, $article['title']);
        $this->assertEquals($description, $article['description']);
        $this->assertEquals($body, $article['body']);
        $this->assertEquals($tagList, $article['tagList']);
        $this->assertEquals(false, $article['favorited']);
        $this->assertEquals(0, $article['favoritesCount']);
        $this->assertEquals('user1', $article['author']['username']);
    }

    public function testUpdate(): void
    {
        $token = $this->getToken('user1@app.test', 'pswd1');

        $title = 'Updated Article';
        $description = 'Updated Description';
        $body = 'Updated Body';
        $tagList = ['test'];

        $article = $this->requestArticles('PUT', 'article-1', token: $token, data: [
            'title' => $title,
            'description' => $description,
            'body' => $body,
            'tagList' => $tagList,
        ]);

        $this->assertMatchesArticleJsonSchema('update');

        $this->assertEquals('updated-article', $article['slug']);
        $this->assertEquals($title, $article['title']);
        $this->assertEquals($description, $article['description']);
        $this->assertEquals($body, $article['body']);
        $this->assertEquals($tagList, $article['tagList']);
        $this->assertEquals(false, $article['favorited']);
        $this->assertEquals(8, $article['favoritesCount']);
    }

    public function testDelete(): void
    {
        $token = $this->getToken('user1@app.test', 'pswd1');

        $this->requestArticles('DELETE', 'article-1', token: $token);
    }

    public function testFavorite(): void
    {
        $slug = 'article-2';

        $token = $this->getToken('user1@app.test', 'pswd1');

        $this->assertFavorited($token, $slug, false);

        $article = $this->requestArticles('POST', $slug . '/favorite', token: $token);

        $this->assertMatchesArticleJsonSchema('favorite');

        $this->assertEquals($slug, $article['slug']);
        $this->assertEquals(1, $article['favoritesCount']);
        $this->assertEquals(true, $article['favorited']);
    }

    public function testUnfavorite(): void
    {
        $slug = 'article-1';

        $token = $this->getToken('user2@app.test', 'pswd2');

        $this->assertFavorited($token, $slug, true);

        $article = $this->requestArticles('DELETE', $slug . '/favorite', token: $token);

        $this->assertMatchesArticleJsonSchema('unfavorite');

        $this->assertEquals($slug, $article['slug']);
        $this->assertEquals(7, $article['favoritesCount']);
        $this->assertEquals(false, $article['favorited']);
    }

    public function testFeed(): void
    {
        $token = $this->getToken('user2@app.test', 'pswd2');

        /** @var string[][] $articles */
        [$count, $articles] = $this->requestArticles('GET', 'feed', token: $token);

        $this->assertMatchesArticleJsonSchema('feed');

        $this->assertEquals(1, $count);
        $this->assertCount(1, $articles);

        /** @var string[][] */
        $article = $articles[0];
        $this->assertEquals('article-1', $article['slug']);
        $this->assertEquals('Article 1', $article['title']);
        $this->assertEquals('Description 1', $article['description']);
        $this->assertEquals('Body 1', $article['body']);
        $this->assertEquals(['tag1'], $article['tagList']);
        $this->assertEquals(true, $article['favorited']);
        $this->assertEquals(8, $article['favoritesCount']);
        $this->assertEquals('user1', $article['author']['username']);
        $this->assertEquals(true, $article['author']['following']);
    }

    /**
     * @return mixed[]
     */
    public function listDataProvider(): array
    {
        $limit = 1;
        $author = 'user1';
        $tag = 'tag1';
        return [[
            '',
            function ($count, $articles) {
                $this->assertEquals(9, $count);
                $this->assertCount(9, $articles);
            }
        ], [
            'limit=' . $limit,
            function ($count, $articles) use ($limit) {
                $this->assertEquals(9, $count);
                $this->assertCount($limit, $articles);
            }
        ], [
            'author=' . $author,
            function ($count, $articles) use ($author) {
                $this->assertEquals(1, $count);
                $this->assertCount(1, $articles);
                $this->assertEquals($author, $articles[0]['author']['username']);
            }
        ], [
            'favorited=user2',
            function ($count, $articles) {
                $this->assertEquals(1, $count);
                $this->assertCount(1, $articles);
                $this->assertEquals(true, $articles[0]['favorited']);
            },
            fn () => $this->getToken('user2@app.test', 'pswd2')
        ], [
            'tag=' . $tag,
            function ($count, $articles) use ($tag) {
                $this->assertEquals(1, $count);
                $this->assertCount(1, $articles);
                $this->assertEquals([$tag], $articles[0]['tagList']);
            }
        ], [
            'author=' . $author . '&favorited=user2&tag=' . $tag . '&limit=' . $limit,
            function ($count, $articles) use ($author, $tag, $limit) {
                $this->assertEquals(1, $count);
                $this->assertCount($limit, $articles);
                $this->assertEquals($author, $articles[0]['author']['username']);
                $this->assertEquals(true, $articles[0]['favorited']);
                $this->assertEquals([$tag], $articles[0]['tagList']);
            },
            fn () => $this->getToken('user2@app.test', 'pswd2')
        ]];
    }

    /**
     * @dataProvider listDataProvider
     * @param string $query
     * @param callable $assertFunc
     * @param callable|null $tokenProvider
     * @return void
     */
    public function testList(string $query, callable $assertFunc, ?callable $tokenProvider = null): void
    {
        [$count, $articles] = $this->requestArticles(
            'GET',
            query: $query,
            token: $tokenProvider !== null ? strval(call_user_func($tokenProvider)) : ''
        );

        $this->assertMatchesArticleJsonSchema('list');

        call_user_func($assertFunc, $count, $articles);
    }
}
