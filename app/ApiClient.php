<?php declare(strict_types=1);

namespace App;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;
    private const BASE_URI = 'https://jsonplaceholder.typicode.com/';


    public function __construct()
    {
        $this->client = new Client();
    }

    public function getArticles(string $id = null): array
    {
        if (!Cache::has('articles' . $id)) {
            $articlesResponse = $this->apiRequest(self::BASE_URI . "posts/" . $id);
            Cache::remember('articles' . $id, $articlesResponse);
        } else {
            $articlesResponse = Cache::get('articles' . $id);
        }
        if ($id == null) {
            $responseData = json_decode($articlesResponse);
            $articles = [];
            foreach ($responseData as $article) {
                $articles[] = new Article
                (
                    $article->userId,
                    $article->id,
                    $article->title,
                    $article->body,
                );
            }
            return $articles;
        }
        $user = [];
        $user[] = json_decode($articlesResponse);
        return $user;
    }

    public function getComments(string $id = null): array
    {
        if (!Cache::has('comments' . $id)) {
            $commentsResponse = $this->apiRequest(self::BASE_URI . "comments/" . $id);
            Cache::remember('comments' . $id, $commentsResponse);
        } else {
            $commentsResponse = Cache::get('comments' . $id);
        }
        if (!empty($commentsResponse)) {
            $responseData = json_decode($commentsResponse);
            $comments = [];
            foreach ($responseData as $comment) {
                $comments[] = new Comment
                (
                    $comment->postId,
                    $comment->id,
                    $comment->name,
                    $comment->email,
                    $comment->body,
                );
            }
            return $comments;
        }
        return [];
    }

    public function getUsers(string $id = null): array
    {
        if (!Cache::has('users' . $id)) {
            $usersResponse = $this->apiRequest(self::BASE_URI . "users/" . $id);
            Cache::remember('users' . $id, $usersResponse);
        } else {
            $usersResponse = Cache::get('users' . $id);
        }
        if ($id == null) {
            $responseData = json_decode($usersResponse);
            $users = [];
            foreach ($responseData as $user) {
                $users[] = new User
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
            return $users;

        }
        $user = [];
        $user[] = json_decode($usersResponse);
        return $user;
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