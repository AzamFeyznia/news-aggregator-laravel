<?php

namespace App\Services;

use App\DataTransferObjects\ArticleData;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NewsAPIService
{
    protected $apiKey;
    protected $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://newsapi.org/v2/',
            'timeout'  => 5.0,
        ]);
    }

    public function getArticles(string $query = null, string $category = null, int $pageSize = 10): Collection
    {
        try {
            $response = $this->client->request('GET', 'top-headlines', [
                'query' => [
                    'apiKey' => $this->apiKey,
                    'country' => 'us',
                    'q' => $query,
                    'category' => $category,
                    'pageSize' => $pageSize
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return collect($data['articles'] ?? [])->map(function ($article) {
                return ArticleData::fromArray([
                    'title' => $article['title'] ?? null,
                    'description' => $article['description'] ?? null,
                    'content' => $article['content'] ?? null,
                    'url' => $article['url'] ?? null,
                    'source' => 'NewsAPI',
                    'category' => $article['category'] ?? null,
                    'published_at' => $article['publishedAt'] ?? null,
                ]);
            });
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching articles from NewsAPI: ' . $e->getMessage());
            return collect();
        }
    }

}
