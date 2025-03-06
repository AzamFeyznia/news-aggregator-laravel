<?php

namespace App\Services;

use App\DataTransferObjects\ArticleData;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NewYorkTimesService
{
    protected $apiKey;
    protected $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://api.nytimes.com/svc/search/v2/',  // NYT API base URL
            'timeout'  => 5.0,
        ]);
    }

    public function getArticles(string $query = null, string $category = null, int $pageSize = 10): Collection
    {
        try {
            $response = $this->client->request('GET', 'articlesearch.json', [  // NYT articlesearch endpoint
                'query' => [
                    'api-key' => $this->apiKey,
                    'q' => $query,
                    'fq' => 'news_desk:("' . $category . '")', // Filter by category
                    'page' => 0, // NYT API uses 'page' instead of pageSize
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return collect($data['response']['docs'] ?? [])->map(function ($article) {
                return ArticleData::fromArray([
                    'title' => $article['headline']['main'] ?? null,
                    'description' => $article['snippet'] ?? null,
                    'content' => $article['lead_paragraph'] ?? null,
                    'url' => $article['web_url'] ?? null,
                    'source' => 'New York Times',
                    'category' => $article['news_desk'] ?? null,
                    'published_at' => $article['pub_date'] ?? null,
                ]);
            });
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching articles from New York Times: ' . $e->getMessage());
            return collect(); // Return empty collection in case of error
        }
    }

}
