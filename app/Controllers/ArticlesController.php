<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\NotFoundView;
use App\Core\TwigView;
use App\Core\View;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\Create\CreateArticleService;
use App\Services\Article\Delete\DeleteArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Create\CreateArticleRequest;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;
use App\Services\Article\Update\UpdateArticleRequest;
use App\Services\Article\Update\UpdateArticleService;

class ArticlesController
{
    private IndexArticleService $indexArticleService;
    private ShowArticleService $showArticleService;
    private CreateArticleService $createArticleService;
    private UpdateArticleService $updateArticleService;
    private DeleteArticleService $deleteArticleService;

    public function __construct
    (
        IndexArticleService  $indexArticleService,
        ShowArticleService   $showArticleService,
        CreateArticleService $createArticleService,
        UpdateArticleService $updateArticleService,
        DeleteArticleService $deleteArticleService
    )
    {
        $this->indexArticleService = $indexArticleService;
        $this->showArticleService = $showArticleService;
        $this->createArticleService = $createArticleService;
        $this->updateArticleService = $updateArticleService;
        $this->deleteArticleService = $deleteArticleService;
    }

    public function index(): View
    {

        $articles = $this->indexArticleService->execute();

        return new TwigView("articles", ['articles' => $articles]);
    }

    public function show(array $vars): View
    {
        try {
            $articleId = (int)$vars['id'];

            $response = $this->showArticleService->execute(new ShowArticleRequest($articleId));

            return new TwigView("singleArticle", [
                'article' => $response->getArticle(),
                'comments' => $response->getComments()
            ]);
        } catch (ResourceNotFoundException $exception) {
            return new NotFoundView("notFound");
        }
    }

    public function create(): View
    {
        return new TwigView('articles/create', []);
    }

    public function store()
    {

        $createArticleResponse = $this->createArticleService->execute(
            new CreateArticleRequest(
                filter_var($_POST['title'], FILTER_SANITIZE_STRING),
                filter_var($_POST['content'], FILTER_SANITIZE_STRING)
            )
        );
        $article = $createArticleResponse->getArticle();

        header('Location: /articles/' . $article->getId());
    }

    public function edit(array $vars): View
    {
        try {
            $response = $this->showArticleService->execute(
                new ShowArticleRequest((int)$vars['id'])
            );
            return new TwigView('articles/edit', ['article' => $response->getArticle()]);
        } catch (\Error $error) {
            return new NotFoundView("notFound");
        }
    }

    public function update(array $vars)
    {
        $response = $this->updateArticleService->execute(
            new UpdateArticleRequest(
                (int)$vars['id'],
                filter_var($_POST['title'], FILTER_SANITIZE_STRING),
                filter_var($_POST['content'], FILTER_SANITIZE_STRING)
            )
        );
        $article = $response->getArticle();

        header('Location: /articles/' . $article->getId());
    }

    public function delete(): void
    {
        $articleId = (int)$_POST['delete'];

        $this->deleteArticleService->execute($articleId);

        header('Location: /articles');
    }
}

