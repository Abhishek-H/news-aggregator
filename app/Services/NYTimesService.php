<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NYTimesService
{
    protected $baseUrl = 'https://api.nytimes.com/svc/topstories/v2/';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NYTIMES_KEY');
    }

    public function fetchArticles($section = 'technology')
    {
        $response = Http::get($this->baseUrl . $section . '.json', [
            'api-key' => $this->apiKey,
        ]);

        return $response->json()['results'] ?? [];
    }
}
