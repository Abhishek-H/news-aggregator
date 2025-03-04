<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TheGuardianService
{
    protected $baseUrl = 'https://content.guardianapis.com/';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GUARDIAN_API_KEY');
    }

    public function fetchArticles($section = 'technology')
    {
        $response = Http::get($this->baseUrl . 'search', [
            'api-key' => $this->apiKey,
            'section' => $section,
            'show-fields' => 'headline,trailText,thumbnail,bodyText,publication',
            'order-by' => 'newest',
            'page-size' => 10, // Fetch 10 latest articles
        ]);

        return $response->json()['response']['results'] ?? [];
    }
}
