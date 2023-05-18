<?php declare(strict_types=1);

namespace App\Commands;

use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ConsoleArticlesController
{
    public function index(): void
    {
        $service = new IndexArticleService();
        $articles = $service->execute();

        (new ConsoleRenderer())->renderArticles($articles);
    }
    public function show(int $id): void
    {
        $articleId = $id;
        $service = new ShowArticleService();
        $response = $service->execute(new ShowArticleRequest($articleId));

        $article = $response->getArticle();
        $comments = $response->getComments();

        (new ConsoleRenderer())->renderSingleArticle($article, $comments);
    }
}
