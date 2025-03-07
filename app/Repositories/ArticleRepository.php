<?php

namespace App\Repositories;

use App\DataTransferObjects\ArticleData;
use App\Models\Article;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        DB::beginTransaction();

        try {
            Article::firstOrCreate(
                ['url' => $articleData->url], // Find by URL
                [
                    'title' => $articleData->title,
                    'description' => $articleData->description,
                    'content' => $articleData->content,
                    'source' => $articleData->source,
                    'category' => $articleData->category,
                    'published_at' => $articleData->published_at,
                ]
            );
            Log::info('Article updated or created: ' . $articleData->url);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error saving article: ' . $e->getMessage());
            throw $e;
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
        DB::beginTransaction();

        try {
            $records = $articleDataCollection
                ->map(function (ArticleData $data) {
                    return [
                        'title' => $data->title,
                        'description' => $data->description,
                        'content' => $data->content,
                        'url' => $data->url,
                        'source' => $data->source,
                        'category' => $data->category,
                        'published_at' => $data->published_at,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })
                ->toArray();

            Article::insertOrIgnore($records);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error saving multiple articles: ' . $e->getMessage());
            throw $e;
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
