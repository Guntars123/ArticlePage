<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Cache;
use App\Models\Article;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class JsonPlaceholderArticleRepository implements ArticleRepository
{
    private Client $client;
    private const BASE_URI = 'https://jsonplaceholder.typicode.com/';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function all(): array
    {
        if (!Cache::has('articles')) {
            $articlesResponse = $this->apiRequest(self::BASE_URI . "posts/");
            Cache::remember('articles', $articlesResponse);
        } else {
            $articlesResponse = Cache::get('articles');
        }
        if (!empty($articlesResponse)) {
            $responseData = json_decode($articlesResponse);
            $articles = [];
            foreach ($responseData as $article) {
                $articles[] = $this->buildModel($article);
            }
            return $articles;
        }
        return [];
    }

    public function getById(int $id): ?Article
    {
        if (!Cache::has('article' . $id)) {
            $articlesResponse = $this->apiRequest(self::BASE_URI . "posts/" . $id);
            Cache::remember('article' . $id, $articlesResponse);
        } else {
            $articlesResponse = Cache::get('article' . $id);
        }
        if (!empty($articlesResponse)) {
            $responseData = json_decode($articlesResponse);

            return $this->buildModel($responseData);
        }
        return null;
    }

    public function getByUserId(int $userId): array
    {
        if (!Cache::has('articles.user' . $userId)) {
            $articlesResponse = $this->apiRequest(self::BASE_URI . "posts?userId=" . $userId);
            Cache::remember('articles.user' . $userId, $articlesResponse);
        } else {
            $articlesResponse = Cache::get('articles.user' . $userId);
        }
        if (!empty($articlesResponse)) {
            $responseData = json_decode($articlesResponse);
            $articles = [];
            foreach ($responseData as $article) {
                $articles[] = $this->buildModel($article);
            }
            return $articles;
        }
        return [];
    }

    private function buildModel(stdClass $article): Article
    {
        return new Article
        (
            $article->userId,
            $article->title,
            $article->body,
            Carbon::now()->toDateTimeString(),
            $article->id
        );
    }

    private function apiRequest(string $url): string
    {
        try {
            $response = $this->client->get($url);
            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            return "";
        }
    }

    public function save(Article $article): void
    {

    }
}