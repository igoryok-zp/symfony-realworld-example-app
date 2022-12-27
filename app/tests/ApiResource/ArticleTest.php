<?php

declare(strict_types=1);

namespace App\Tests\ApiResource;

use App\ApiResource\Article;

class ArticleTest extends ApiResourceTestCase
{
    private function assertMatchesArticleJsonSchema(string $operationName)
    {
        $this->assertMatchesApiResourceJsonSchema(Article::class, 'article_' . $operationName);
    }

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
        $result = $this->requestApi($method, $articleApi, $articleData, $token);
        return $result['article'] ?? array_filter([
            $result['articlesCount'] ?? null,
            $result['articles'] ?? null,
        ]);
    }

    public function testGet()
    {
        $slug = 'article-1';

        $article = $this->requestArticles('GET', $slug);

        $this->assertMatchesArticleJsonSchema('get');

        $this->assertEquals($slug, $article['slug']);
        $this->assertEquals('Article 1', $article['title']);
        $this->assertEquals('Description 1', $article['description']);
        $this->assertEquals('Body 1', $article['body']);
        $this->assertEquals('user1', $article['author']['username']);
    }
}
