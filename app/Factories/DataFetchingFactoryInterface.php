<?php

namespace App\Factories;

use App\Services\DataFetchingServiceInterface;

interface DataFetchingFactoryInterface
{
    /**
     * Creates a data fetching service based on the given source.
     *
     * @param string $source
     * @return DataFetchingServiceInterface
     * @throws \InvalidArgumentException
     */
    public function create(string $source): DataFetchingServiceInterface;

}
