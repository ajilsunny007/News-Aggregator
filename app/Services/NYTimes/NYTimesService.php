<?php

namespace App\Services\NYTimes;

use Illuminate\Support\Facades\Http;

class NYTimesService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.nytimes.com/svc';

    public function __construct()
    {
        $this->apiKey = config('services.nytimes.key');
    }

    public function fetch()
    {
        $response = Http::get("{$this->baseUrl}/news/v3/content/all/all.json", [
            'api-key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch from NY Times');
        }

        return $this->formatArticles($response->json()['results']);
    }

    protected function formatArticles($articles)
    {
        return collect($articles)->map(function ($article) {
            return [
                'title' => $article['title'],
                'content' => $article['abstract'],
                'source' => 'The New York Times',
                'author' => $article['byline'] ?? null,
                'url' => $article['url'],
                'category' => $article['section'],
                'external_id' => $article['uri'],
                'published_at' => $article['published_date'],
            ];
        })->all();
    }
}
