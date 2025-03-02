<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::create([
            'title' => 'Breaking News: Laravel 12 Released!',
            'source' => 'NewsAPI',
            'author' => 'John Doe',
            'category' => 'Technology',
            'content' => 'Laravel 12 is officially released with exciting new features!',
            'url' => 'https://laravel.com/docs/12.x/',
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/255px-Laravel.svg.png',
            'published_at' => now(),
        ]);
    }
}
