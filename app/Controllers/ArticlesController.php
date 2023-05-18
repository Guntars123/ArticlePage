<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\NotFoundView;
use App\Core\TwigView;
use App\Core\View;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;


class ArticlesController
{
    public function index(): View
    {
        $service = new IndexArticleService();
        $articles = $service->execute();

        return new TwigView("index", ['articles' => $articles]);
    }

    public function show(array $vars): View
    {

        try {
            $articleId = (int)$vars['id'];
            $service = new ShowArticleService();
            $response = $service->execute(new ShowArticleRequest($articleId));

            return new TwigView("article", [
                'article' => $response->getArticle(),
                'comments' => $response->getComments()
            ]);
        } catch (ResourceNotFoundException $exception) {
            return new NotFoundView("notFound");
        }
    }
}

