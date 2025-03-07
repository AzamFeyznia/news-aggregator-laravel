<?php

namespace Unit\Services;

use App\DataTransferObjects\ArticleData;
use App\Services\NewsAPIService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NewsAPIServiceTest extends TestCase
{
    public function test_get_articles_returns_a_collection_of_articledata_objects()
    {
        // Arrange
        $apiKey = 'test_api_key';
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../../Stubs/newsapi_response.json')),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new NewsAPIService($apiKey);
        $service->setClient($client);

        // Act
        $articles = $service->getArticles();

        // Assert
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertCount(2, $articles); // Assuming the stub has 2 articles
        $this->assertEquals('Title 1', $articles[0]->title);
        $this->assertEquals('Description 1', $articles[0]->description);
    }

    public function test_get_articles_handles_api_errors()
    {
        // Arrange
        $apiKey = 'test_api_key';
        $mock = new MockHandler([
            new Response(500, [], '{"error": "Internal Server Error"}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new NewsAPIService($apiKey);
        $service->setClient($client);

        // Act
        $articles = $service->getArticles();

        // Assert
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertCount(0, $articles);
    }

    public function test_articledata_create_with_nullable_values()
    {
        $articleData = new ArticleData(
            title: "hello",
            description: null,
            content: null,
            url: "http://example.com",
            source: "source",
            category: null,
            published_at: null
        );

        $this->assertEquals("hello", $articleData->title);
        $this->assertEquals(null, $articleData->description);
        $this->assertEquals(null, $articleData->published_at);
    }

    public function test_articledata_create_valid_values()
    {
        $title = "hello";
        $description = "test";
        $content = "test";
        $url = "http://example.com";
        $source = "test";
        $category = "test";
        $published_at = Carbon::now();

        $articleData = new ArticleData(
            title: $title,
            description: $description,
            content: $content,
            url: $url,
            source: $source,
            category: $category,
            published_at: $published_at
        );

        $this->assertEquals($title, $articleData->title);
        $this->assertEquals($description, $articleData->description);
        $this->assertEquals($url, $articleData->url);
        $this->assertEquals($source, $articleData->source);
        $this->assertEquals($published_at, $articleData->published_at);
    }

    public function test_get_articles_handles_empty_response()
    {
        // Arrange
        $apiKey = 'test_api_key';
        $mock = new MockHandler([
            new Response(200, [], '{"status": "ok", "totalResults": 0, "articles": []}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new NewsAPIService($apiKey);
        $service->setClient($client);

        // Act
        $articles = $service->getArticles();

        // Assert
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertEmpty($articles);
    }

    public function test_get_articles_handles_invalid_json_response()
    {
        // Arrange
        $apiKey = 'test_api_key';
        $mock = new MockHandler([
            new Response(200, [], 'Invalid JSON'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new NewsAPIService($apiKey);
        $service->setClient($client);

        // Act
        $articles = $service->getArticles();

        // Assert
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertEmpty($articles);
    }

}
