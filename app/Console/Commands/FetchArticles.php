<?php

namespace app\Console\Commands;

use App\Repositories\ArticleRepositoryInterface;
use App\Factories\DataFetchingFactoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchArticles extends Command
{
    protected $signature = 'fetch:articles';

    protected $description = 'Fetch articles from various sources and store them in the database';

    public function __construct(
        protected ArticleRepositoryInterface $articleRepository,
        protected DataFetchingFactoryInterface $dataFetchingFactory,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Fetching articles...');

        $sources = ['newsapi', 'guardian', 'nytimes'];
        $allArticles = collect();

        foreach ($sources as $source) {
            try {
                $service = $this->dataFetchingFactory->create($source);
                $articles = $service->getArticles();
                $allArticles = $allArticles->concat($articles);
            } catch (\InvalidArgumentException $e) {
                Log::error("Error fetching articles from $source: " . $e->getMessage());
                $this->error("Error fetching articles from $source: " . $e->getMessage());
                continue; // Skip to the next source
            }
        }

        $this->articleRepository->saveMany($allArticles);

        $this->info('Articles fetched and stored successfully.');

        return Command::SUCCESS;
    }

}
