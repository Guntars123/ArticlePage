<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

class ArticlesController
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }


    public function index(): TwigView
    {
        $articles = $this->apiClient->getArticles();
        $users = $this->apiClient->getUsers();

        return new TwigView("index", ['articles' => $articles, 'users' => $users]);
    }

    public function user(array $vars): TwigView
    {
        $userId = $vars["id"];

        $user = $this->apiClient->getUsers($userId)[0];
        $articles = $this->apiClient->getArticles();

        return new TwigView("user", ['user' => $user, 'articles' => $articles]);
    }

    public function article(array $vars): TwigView
    {
        $articleId = $vars['id'];

        $users = $this->apiClient->getUsers();
        $article = $this->apiClient->getArticles($articleId)[0];
        $comments = $this->apiClient->getComments();

        return new TwigView("article", ['users' => $users, 'article' => $article, 'comments' => $comments]);
    }
}

