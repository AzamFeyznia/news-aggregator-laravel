<?php

namespace Feature\Repositories;

use App\DataTransferObjects\ArticleData;
use App\Models\Article;
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ArticleRepositoryInterface $articleRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->articleRepository = $this->app->make(ArticleRepositoryInterface::class); // Resolve from container
    }

    public function test_save_creates_a_new_article()
    {
        $articleData = new ArticleData(
            title: 'Test Title',
            description: 'Test Description',
            content: 'Test Content',
            url: 'http://example.com/test',
            source: 'Test Source',
            category: 'Test Category',
            published_at: now()
        );

        $this->articleRepository->save($articleData);

        $this->assertDatabaseHas('articles', ['title' => 'Test Title']);
    }

    public function test_save_creates_a_new_article_with_nullable_fields()
    {
        $articleData = new ArticleData(
            title: 'Test Title',
            description: null,
            content: null,
            url: 'http://example.com/test',
            source: 'Test Source',
            category: null,
            published_at: null
        );

        $this->articleRepository->save($articleData);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Title',
            'url' => 'http://example.com/test',
            'description' => null,
            'content' => null,
            'category' => null,
            'published_at' => null,
        ]);
    }

    public function test_save_does_not_create_a_new_article_if_url_exists()
    {
        // Arrange
        Article::factory()->create(['url' => 'http://example.com/test']);

        $articleData = new ArticleData(
            title: 'Updated Title',
            description: 'Updated Description',
            content: 'Updated Content',
            url: 'http://example.com/test',
            source: 'Updated Source',
            category: 'Updated Category',
            published_at: now()
        );

        // Act
        $this->articleRepository->save($articleData);

        // Assert
        $this->assertDatabaseCount('articles', 1); // Still only one article
    }

    public function test_savemany_creates_multiple_articles()
    {
        $articleData1 = new ArticleData(
            title: 'Test Title 1',
            description: 'Test Description 1',
            content: 'Test Content 1',
            url: 'http://example.com/test1',
            source: 'Test Source 1',
            category: 'Test Category 1',
            published_at: now()
        );

        $articleData2 = new ArticleData(
            title: 'Test Title 2',
            description: 'Test Description 2',
            content: 'Test Content 2',
            url: 'http://example.com/test2',
            source: 'Test Source 2',
            category: 'Test Category 2',
            published_at: now()
        );

        $articleDataCollection = collect([$articleData1, $articleData2]);

        $this->articleRepository->saveMany($articleDataCollection);

        $this->assertDatabaseHas('articles', ['title' => 'Test Title 1']);
        $this->assertDatabaseHas('articles', ['title' => 'Test Title 2']);
    }

    public function test_getAll_returns_all_articles()
    {
        Article::factory()->count(3)->create();

        $articles = $this->articleRepository->getAll();

        $this->assertCount(3, $articles);
    }

    public function test_search_returns_articles_matching_the_criteria()
    {
        // Arrange
        Article::factory()->create(['title' => 'Laravel Article', 'source' => 'NewsAPI']);
        Article::factory()->create(['title' => 'PHP Article', 'source' => 'The Guardian']);
        Article::factory()->create(['title' => 'Another Article', 'source' => 'NewsAPI']);

        // Act
        $criteria = ['q' => 'Laravel', 'source' => 'NewsAPI'];
        $articles = $this->articleRepository->search($criteria);

        // Assert
        $this->assertCount(1, $articles);
        $this->assertEquals('Laravel Article', $articles->first()->title);
    }

}
