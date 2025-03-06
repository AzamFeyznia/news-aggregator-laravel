<?php

namespace App\Factories;

use App\Services\NewsAPIService;
use App\Services\GuardianAPIService;
use App\Services\NewYorkTimesService;
use App\Services\DataFetchingServiceInterface;
use InvalidArgumentException;

class DataFetchingFactory implements DataFetchingFactoryInterface
{
    /**
     * Creates a data fetching service based on the given source.
     *
     * @param string $source
     * @return DataFetchingServiceInterface
     * @throws \InvalidArgumentException
     */
    public function create(string $source): DataFetchingServiceInterface
    {
        switch ($source) {
            case 'newsapi':
                return app(NewsAPIService::class); // Resolve from the container
            case 'guardian':
                return app(GuardianAPIService::class); // Resolve from the container
            case 'nytimes':
                return app(NewYorkTimesService::class); // Resolve from the container
            default:
                throw new InvalidArgumentException("Invalid data source: $source");
        }
    }

}
