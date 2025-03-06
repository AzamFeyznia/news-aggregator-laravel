<?php

namespace app\Console\Commands;

use App\Jobs\FetchArticlesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchArticles extends Command
{
    protected $signature = 'fetch:articles';

    protected $description = 'Fetch articles from various sources and store them in the database';

    public function handle(): int
    {
        Log::info('FetchArticles command dispatched.');
        FetchArticlesJob::dispatch();  // Dispatch the job

        $this->info('Data fetching job dispatched to the queue.'); // Inform to the console
        return Command::SUCCESS;
    }

}
