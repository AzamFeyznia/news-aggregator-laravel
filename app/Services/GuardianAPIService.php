<?php

namespace App\Services;

use App\DataTransferObjects\ArticleData;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GuardianAPIService
{
    protected $apiKey;
    protected $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://content.guardianapis.com/',
            'timeout'  => 5.0,
        ]);
    }

    public function getArticles(string $query = null, int $pageSize = 10): Collection
    {
        try {
            $response = $this->client->request('GET', 'search', [
                'query' => [
                    'api-key' => $this->apiKey,
                    'q' => $query,
                    'page-size' => $pageSize
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return collect($data['response']['results'] ?? [])->map(function ($article) {
                return ArticleData::fromArray([
                    'title' => $article['webTitle'] ?? null,
                    'description' => null, // The Guardian API doesn't have a direct description field
                    'content' => null, // We can fetch content from each article page if necessary
                    'url' => $article['webUrl'] ?? null,
                    'source' => 'The Guardian',
                    'category' => $article['sectionName'] ?? null,
                    'published_at' => $article['webPublicationDate'] ?? null,
                ]);
            });
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching articles from The Guardian: ' . $e->getMessage());
            return collect(); // Return empty collection in case of error
        }
    }

}
