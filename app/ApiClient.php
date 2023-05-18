<?php declare(strict_types=1);

namespace App;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ApiClient
{
    private Client $client;
    private const BASE_URI = 'https://jsonplaceholder.typicode.com/';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getArticles(): array
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
                $articles[] = $this->createArticle($article);
            }
            return $articles;
        }
        return [];
    }

    public function getUsers(): array
    {
        if (!Cache::has('users')) {
            $usersResponse = $this->apiRequest(self::BASE_URI . "users/");
            Cache::remember('users', $usersResponse);
        } else {
            $usersResponse = Cache::get('users');
        }
        if (!empty($usersResponse)) {
            $responseData = json_decode($usersResponse);
            $users = [];
            foreach ($responseData as $user) {
                $users[] = $this->createUser($user);
            }
            return $users;
        }
        return [];
    }

    public function getCommentsByArticleId(int $id): array
    {
        if (!Cache::has('comments' . $id)) {
            $commentsResponse = $this->apiRequest(self::BASE_URI . "comments?postId=" . $id);
            Cache::remember('comments' . $id, $commentsResponse);
        } else {
            $commentsResponse = Cache::get('comments' . $id);
        }
        if (!empty($commentsResponse)) {
            $responseData = json_decode($commentsResponse);
            $comments = [];
            foreach ($responseData as $comment) {
                $comments[] = $this->createComment($comment);
            }
            return $comments;
        }
        return [];
    }

    public function getSingleUser(int $id): ?User
    {
        if (!Cache::has('user' . $id)) {
            $usersResponse = $this->apiRequest(self::BASE_URI . "users/" . $id);
            Cache::remember('user' . $id, $usersResponse);
        } else {
            $usersResponse = Cache::get('user' . $id);
        }
        if (!empty($usersResponse)) {
            $responseData = json_decode($usersResponse);
            return $this->createUser($responseData);
        }
        return null;
    }

    public function getArticlesByUserId(int $id): array
    {
        if (!Cache::has('articles.user' . $id)) {
            $articlesResponse = $this->apiRequest(self::BASE_URI . "posts?userId=" . $id);
            Cache::remember('articles.user' . $id, $articlesResponse);
        } else {
            $articlesResponse = Cache::get('articles.user' . $id);
        }
        if (!empty($articlesResponse)) {
            $responseData = json_decode($articlesResponse);
            $articles = [];
            foreach ($responseData as $article) {
                $articles[] = $this->createArticle($article);
            }
            return $articles;
        }
        return [];
    }

    public function getArticleByArticleId(int $id): ?Article
    {
        if (!Cache::has('article' . $id)) {
            $articlesResponse = $this->apiRequest(self::BASE_URI . "posts/" . $id);
            Cache::remember('article' . $id, $articlesResponse);
        } else {
            $articlesResponse = Cache::get('article' . $id);
        }
        if (!empty($articlesResponse)) {
            $responseData = json_decode($articlesResponse);

            return $this->createArticle($responseData);
        }
        return null;
    }

    private function createArticle(stdClass $article): Article
    {
        return new Article
        (
            $this->getSingleUser($article->userId),
            $article->id,
            $article->title,
            $article->body,
        );
    }

    private function createUser(stdClass $user): User
    {
        return new User
        (
            $user->id,
            $user->name,
            $user->username,
            $user->email,
            $user->address,
            $user->phone,
            $user->website,
            $user->company
        );
    }

    private function createComment(stdClass $comment): Comment
    {
        return new Comment
        (
            $comment->postId,
            $comment->id,
            $comment->name,
            $comment->email,
            $comment->body,
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

}