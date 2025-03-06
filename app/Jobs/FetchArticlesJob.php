<?php

namespace App\Jobs;

use App\Repositories\ArticleRepositoryInterface;
use App\Services\DataFetchingServiceInterface;
use App\Factories\DataFetchingFactoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(DataFetchingFactoryInterface $dataFetchingFactory, ArticleRepositoryInterface $articleRepository): void
    {
        Log::info('FetchArticlesJob started.');

        $sources = ['newsapi', 'guardian', 'nytimes'];

        foreach ($sources as $source) {
            try {
                $service = $dataFetchingFactory->create($source);
                $articles = $service->getArticles();
                $articleRepository->saveMany($articles);
            } catch (\Exception $e) {
                Log::error("Error fetching articles from $source: " . $e->getMessage());
            }
        }

        Log::info('FetchArticlesJob finished.');
    }
}
