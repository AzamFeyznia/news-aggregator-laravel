<?php

namespace Feature\Api;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_returns_a_paginated_list_of_articles()
    {
        Article::factory()->count(30)->create(); // Create 30 articles

        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'title', 'description', 'content', 'url', 'source', 'category', 'published_at', 'created_at', 'updated_at'
                    ]
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }

    public function test_index_returns_articles_based_on_source_filter()
    {
        // Arrange
        Article::factory()->count(5)->create(['source' => 'NewsAPI']);
        Article::factory()->count(3)->create(['source' => 'Guardian']);

        // Act
        $response = $this->getJson('/api/v1/articles?source=NewsAPI');

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data'); // Assert that 5 articles are returned
    }

    public function test_index_returns_articles_based_on_category_filter()
    {
        // Arrange
        Article::factory()->count(4)->create(['category' => 'Technology']);
        Article::factory()->count(2)->create(['category' => 'Sports']);

        // Act
        $response = $this->getJson('/api/v1/articles?category=Technology');

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(4, 'data'); // Assert that 4 articles are returned
    }

    public function test_index_returns_articles_based_on_search_term()
    {
        // Arrange
        Article::factory()->create(['title' => 'Laravel News']);
        Article::factory()->create(['title' => 'PHP News']);
        Article::factory()->create(['title' => 'Another Laravel']);

        // Act
        $response = $this->getJson('/api/v1/articles?q=Laravel');

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data'); // Assert that 2 articles are returned
    }

    public function test_show_returns_a_specific_article()
    {
        // Arrange
        $article = Article::factory()->create();

        // Act
        $response = $this->getJson("/api/v1/articles/{$article->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'description' => $article->description,
                    'content' => $article->content,
                    'url' => $article->url,
                    'source' => $article->source,
                    'category' => $article->category,
                    'published_at' => $article->published_at->format('Y-m-d\TH:i:s.u\Z'),
                    'created_at' => $article->created_at->format('Y-m-d\TH:i:s.u\Z'),
                    'updated_at' => $article->updated_at->format('Y-m-d\TH:i:s.u\Z'),
                ]
            ]);
    }

}
