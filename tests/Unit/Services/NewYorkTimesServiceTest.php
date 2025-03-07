<?php

namespace Unit\Services;

use App\Services\NewYorkTimesService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NewYorkTimesServiceTest extends TestCase
{
    public function test_get_articles_returns_a_collection_of_articledata_objects()
    {
        // Arrange
        $apiKey = 'test_api_key';
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../../Stubs/nytimes_response.json')),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new NewYorkTimesService($apiKey);
        $service->setClient($client);

        // Act
        $articles = $service->getArticles();

        // Assert
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertCount(2, $articles); // Assuming the stub has 2 articles
        $this->assertEquals('Title 1', $articles[0]->title);
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

        $service = new NewYorkTimesService($apiKey);
        $service->setClient($client);

        // Act
        $articles = $service->getArticles();

        // Assert
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertCount(0, $articles);
    }

    public function test_get_articles_handles_empty_response()
    {
        // Arrange
        $apiKey = 'test_api_key';
        $mock = new MockHandler([
            new Response(200, [], '{"response": {"docs": []}}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new NewYorkTimesService($apiKey);
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

        $service = new NewYorkTimesService($apiKey);
        $service->setClient($client);

        // Act
        $articles = $service->getArticles();

        // Assert
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertEmpty($articles);
    }


}
