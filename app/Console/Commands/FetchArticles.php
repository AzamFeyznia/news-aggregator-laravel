<?php

namespace app\Console\Commands;

use App\Repositories\ArticleRepositoryInterface;
use App\Services\GuardianAPIService;
use App\Services\NewsAPIService;
use App\Services\NewYorkTimesService;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    protected $signature = 'fetch:articles';

    protected $description = 'Fetch articles from various sources and store them in the database';

    public function __construct(
        protected NewsAPIService $newsApiService,
        protected GuardianAPIService $guardianApiService,
        protected NewYorkTimesService $newYorkTimesService,
        protected ArticleRepositoryInterface $articleRepository
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Fetching articles...');

        $newsArticles = $this->newsApiService->getArticles();
        $guardianArticles = $this->guardianApiService->getArticles();
        $newYorkTimesArticles = $this->newYorkTimesService->getArticles();

        $allArticles = $newsArticles->concat($guardianArticles)->concat($newYorkTimesArticles);

        $this->articleRepository->saveMany($allArticles);

        $this->info('Articles fetched and stored successfully.');

        return Command::SUCCESS;
    }

}
