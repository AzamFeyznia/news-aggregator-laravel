<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Services\GuardianAPIService;
use App\Services\NewsAPIService;
use App\Services\NewYorkTimesService;
use Illuminate\Support\ServiceProvider;

class DataFetchingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the ArticleRepositoryInterface to its implementation
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);

        // Bind the NewsAPIService to the container
        $this->app->bind(NewsAPIService::class, function ($app) {
            return new NewsAPIService(config('services.newsapi.key'));
        });

        // Bind the GuardianAPIService to the container
        $this->app->bind(GuardianAPIService::class, function ($app) {
            return new GuardianAPIService(config('services.guardian.key'));
        });

        // Bind the NewYorkTimesService to the container
        $this->app->bind(NewYorkTimesService::class, function ($app) {
            return new NewYorkTimesService(config('services.nytimes.key'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }


}
