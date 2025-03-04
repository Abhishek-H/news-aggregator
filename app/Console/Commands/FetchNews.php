<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAPIService;
use App\Models\Article;
use App\Services\NYTimesService;
use App\Services\TheGuardianService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FetchNews extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch latest news articles from multiple sources';

    public function handle()
    {
        $this->fetchFromNewsAPI();
        $this->fetchFromTheGuardian();
        $this->fetchFromNYTimes();

        $this->info('News articles updated successfully from all sources.');
    }


    private function fetchFromNewsAPI()
    {
        $newsService = new NewsAPIService();
        $articles = $newsService->fetchArticles();

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['title']],
                [
                    'source' => $article['source']['name'],
                    'author' => $article['author'] ?? 'Unknown',
                    'category' => 'Technology',
                    'content' => $article['description'] ?? 'No content available',
                    'url' => $article['url'],
                    'image_url' => $article['urlToImage'] ?? null,
                    'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
                ]
            );
        }
    }


    private function fetchFromTheGuardian()
    {
        $newsService = new TheGuardianService();
        $articles = $newsService->fetchArticles();

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['webTitle']],
                [
                    'source' => 'The Guardian',
                    'author' => 'Unknown',
                    'category' => $article['sectionName'] ?? 'General',
                    'content' => $article['fields']['trailText'] ?? 'No content available',
                    'url' => $article['webUrl'],
                    'image_url' => $article['fields']['thumbnail'] ?? null,
                    'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
                ]
            );
        }
    }


    private function fetchFromNYTimes()
    {
        $newsService = new NYTimesService();
        $articles = $newsService->fetchArticles();

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['title']],
                [
                    'source' => 'New York Times',
                    'author' => $article['byline'] ?? 'Unknown',
                    'category' => $article['section'] ?? 'General',
                    'content' => $article['abstract'] ?? 'No content available',
                    'url' => $article['url'],
                    'image_url' => $article['multimedia'][0]['url'] ?? null,
                    'published_at' => Carbon::parse($article['published_date'])->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
