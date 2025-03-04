# 📰 Laravel News Aggregator

A Laravel-based news aggregator that fetches and stores articles from multiple news sources using Docker.

---

## 📌 Prerequisites

Ensure you have the following installed before running the project:

- [Docker](https://www.docker.com/get-started/)
- Docker Compose (Included with Docker Desktop)
- API Keys from news sources:
  - [NewsAPI](https://newsapi.org/)
  - [OpenNews](https://opennews.com/)
  - [The Guardian](https://open-platform.theguardian.com/access/)
  - [New York Times](https://developer.nytimes.com/)

---

## 📂 1. Clone the Repository

```sh
git clone https://github.com/your-repo/news-aggregator.git
cd news-aggregator

⚙️ 2. Configure Environment Variables

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=news_db
DB_USERNAME=user
DB_PASSWORD=password

# API Keys
NEWSAPI_KEY=your_newsapi_api_key
OPENNEWS_API_KEY=your_opennews_api_key
THE_GUARDIAN_API_KEY=your_guardian_api_key
NYTIMES_API_KEY=your_nytimes_api_key


3. Build & Start Docker Containers
Run:  docker-compose up -d --build

✅ This will start:
laravel_app → Laravel Application
mysql_db → MySQL Database
redis_cache → Redis for Caching
nginx_server → Nginx Web Server
phpmyadmin → MySQL Admin Panel (Optional)


4. Install Dependencies & Set Up Database
Run:
docker exec -it laravel_app composer install
docker exec -it laravel_app php artisan key:generate
docker exec -it laravel_app php artisan migrate --force

5. Fetch News from APIs
Run manually:
docker exec -it laravel_app php artisan news:fetch

✅ This will fetch and store news from multiple sources:

NewsAPI
The Guardian
New York Times


6. Automate News Fetching (Laravel Scheduler)
Run Manually
docker exec -it laravel_app php artisan schedule:run

Run in Background
docker exec -it laravel_app php artisan schedule:work
