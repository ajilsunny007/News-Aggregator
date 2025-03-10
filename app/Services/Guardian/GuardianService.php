<?php

namespace App\Services\Guardian;

use Illuminate\Support\Facades\Http;

class GuardianService
{
    protected $apiKey;
    protected $baseUrl = 'https://content.guardianapis.com';

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
    }

    public function fetch()
    {
        $response = Http::get("{$this->baseUrl}/search", [
            'api-key' => $this->apiKey,
            'show-fields' => 'all',
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch from Guardian');
        }

        return $this->formatArticles($response->json()['response']['results']);
    }

    protected function formatArticles($articles)
    {
        return collect($articles)->map(function ($article) {
            return [
                'title' => $article['webTitle'],
                'content' => $article['fields']['bodyText'] ?? '',
                'source' => 'The Guardian',
                'author' => $article['fields']['byline'] ?? null,
                'url' => $article['webUrl'],
                'category' => $article['sectionName'],
                'external_id' => $article['id'],
                'published_at' => $article['webPublicationDate'],
            ];
        })->all();
    }
}
