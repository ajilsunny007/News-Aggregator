<?php

namespace App\Services\NewsAPI;

use Illuminate\Support\Facades\Http;

class NewsAPIService
{
    protected $apiKey;
    protected $baseUrl = 'https://newsapi.org/v2';

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    public function fetch()
    {
        $response = Http::get("{$this->baseUrl}/top-headlines", [
            'apiKey' => $this->apiKey,
            'language' => 'en',
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch from NewsAPI');
        }

        return $this->formatArticles($response->json()['articles']);
    }

    protected function formatArticles($articles)
    {
        return collect($articles)->map(function ($article) {
            return [
                'title' => $article['title'],
                'content' => $article['description'] ?? '',
                'source' => $article['source']['name'],
                'author' => $article['author'],
                'url' => $article['url'],
                'category' => 'general', // NewsAPI doesn't provide category in top-headlines
                'external_id' => md5($article['url']),
                'published_at' => $article['publishedAt'],
            ];
        })->all();
    }
}
