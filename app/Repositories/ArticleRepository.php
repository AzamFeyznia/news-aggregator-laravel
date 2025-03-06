<?php

namespace App\Repositories;

use App\DataTransferObjects\ArticleData;
use App\Models\Article;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * Saves a new article to the database.
     *
     * @param ArticleData $articleData
     * @return void
     */
    public function save(ArticleData $articleData): void
    {
        $article = Article::where('url', $articleData->url)->first();

        if (!$article) {
            Article::create([
                'title' => $articleData->title,
                'description' => $articleData->description,
                'content' => $articleData->content,
                'url' => $articleData->url,
                'source' => $articleData->source,
                'category' => $articleData->category,
                'published_at' => $articleData->published_at,
            ]);
            Log::info('Article created: ' . $articleData->url);
        }
    }

    /**
     * Saves multiple articles to the database.
     *
     * @param Collection<ArticleData> $articleDataCollection
     * @return void
     */
    public function saveMany(Collection $articleDataCollection): void
    {
        foreach ($articleDataCollection as $articleData) {
            $this->save($articleData);
        }
    }

    /**
     * Retrieves all articles from the database.
     *
     * @return Collection<Article>
     */
    public function getAll(): Collection
    {
        return Article::all();
    }

    /**
     * Finds an article by its ID.
     *
     * @param int $id
     * @return Article|null
     */
    public function findById(int $id): ?Article
    {
        return Article::find($id);
    }

    /**
     * Searches for articles based on the given criteria.
     *
     * @param array $criteria An array of key-value pairs to search by (e.g., ['title' => 'keyword', 'source' => 'The Guardian'])
     * @return Collection<Article>
     */
    public function search(array $criteria): Collection
    {
        $query = Article::query();

        foreach ($criteria as $key => $value) {
            $query->where($key, 'like', '%' . $value . '%');
        }

        return $query->get();
    }

}
