<?php

namespace App\Repositories;

use App\DataTransferObjects\ArticleData;
use App\Models\Article;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
    /**
     * Saves a new article to the database.
     *
     * @param ArticleData $articleData
     * @return void
     */
    public function save(ArticleData $articleData): void;

    /**
     * Saves multiple articles to the database.
     *
     * @param Collection<ArticleData> $articleData
     * @return void
     */
    public function saveMany(Collection $articleData): void;

    /**
     * Retrieves all articles from the database.
     *
     * @return Collection<Article>
     */
    public function getAll(): Collection;

    /**
     * Finds an article by its ID.
     *
     * @param int $id
     * @return Article|null
     */
    public function findById(int $id): ?Article;

    /**
     * Searches for articles based on the given criteria.
     *
     * @param array $criteria An array of key-value pairs to search by (e.g., ['title' => 'keyword', 'source' => 'The Guardian'])
     * @return Collection<Article>
     */
    public function search(array $criteria): Collection;

}
