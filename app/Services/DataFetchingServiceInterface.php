<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface DataFetchingServiceInterface
{
    /**
     * Retrieves articles from the data source.
     *
     * @param string|null $query
     * @param string|null $category
     * @param int $pageSize
     * @return Collection
     */
    public function getArticles(string $query = null, string $category = null, int $pageSize = 10): Collection;

}
