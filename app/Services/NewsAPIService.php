<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAPIService
{
    protected $baseUrl = 'https://newsapi.org/v2/';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NEWS_API_KEY');
    }

    // Fetch top headlines from NewsAPI
    public function fetchArticles($category = 'technology')
    {
        $response = Http::withOptions([
            'verify' => false
        ])->get($this->baseUrl . 'top-headlines', [
            'apiKey' => $this->apiKey,
            'category' => $category,
            'country' => 'us',
        ]);

        return $response->json()['articles'] ?? [];
    }
}
