<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAPIService;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FetchNews extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch latest news articles from NewsAPI';

    public function handle()
    {
        $newsService = new NewsAPIService();
        $articles = $newsService->fetchArticles();

        foreach ($articles as $article) {
            Log::info($article);
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

        $this->info('News articles updated successfully.');
    }
}
