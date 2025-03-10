<?php

namespace App\Console\Commands;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Services\NewsAPI\NewsAPIService;
use App\Services\Guardian\GuardianService;
use App\Services\NYTimes\NYTimesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchNewsCommand extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news from all configured sources';

    protected $articleRepository;
    protected $services;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        parent::__construct();
        $this->articleRepository = $articleRepository;
        $this->services = [
            new NewsAPIService(),
            new GuardianService(),
            new NYTimesService(),
        ];
    }

    public function handle()
    {
        $this->info('Starting news fetch...');

        foreach ($this->services as $service) {
            try {
                $articles = $service->fetch();

                foreach ($articles as $article) {
                    $this->articleRepository->create($article);
                }

                $this->info('Successfully fetched articles from ' . get_class($service));
            } catch (\Exception $e) {
                Log::error('Failed to fetch articles from ' . get_class($service) . ': ' . $e->getMessage());
                $this->error('Failed to fetch from ' . get_class($service));
            }
        }

        $this->info('News fetch completed');
    }
}
