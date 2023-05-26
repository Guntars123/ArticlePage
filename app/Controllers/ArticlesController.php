<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\NotFoundView;
use App\Core\TwigView;
use App\Core\View;
use App\Exceptions\ResourceNotFoundException;
use App\Services\Article\CudArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticlesController
{
    private IndexArticleService $indexArticleService;
    private ShowArticleService $showArticleService;

    private CudArticleService $cudArticleService;

    public function __construct
    (
        IndexArticleService $indexArticleService,
        ShowArticleService  $showArticleService,
        CudArticleService   $cudArticleService
    )
    {
        $this->indexArticleService = $indexArticleService;
        $this->showArticleService = $showArticleService;
        $this->cudArticleService = $cudArticleService;
    }

    public function index(): View
    {

        $articles = $this->indexArticleService->execute();

        return new TwigView("index", ['articles' => $articles]);
    }

    public function show(array $vars): View
    {
        try {
            $articleId = (int)$vars['id'];

            $response = $this->showArticleService->execute(new ShowArticleRequest($articleId));

            return new TwigView("article", [
                'article' => $response->getArticle(),
                'comments' => $response->getComments()
            ]);
        } catch (ResourceNotFoundException $exception) {
            return new NotFoundView("notFound");
        }
    }

    public function createView(): View
    {
        return new TwigView('addArticle', []);
    }

    public function create(): View
    {
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
        $articleId = $this->cudArticleService->create($title, $content);
        return new TwigView('articleAdded', ['articleId' => $articleId]);
    }

    public function editView(array $vars): View
    {
        $articleId = (int)$vars['id'];
        $response = $this->showArticleService->execute(new ShowArticleRequest($articleId));

        return new TwigView('editArticle', [
            'article' => $response->getArticle()
        ]);
    }

    public function edit(array $vars): View
    {
        $articleId = (int)$vars['id'];
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
        $this->cudArticleService->edit($articleId, $title, $content);
        return new TwigView('articleEdited', ['articleId' => $articleId]);
    }

    public function delete(): View
    {
        $articleId = (int)$_POST['delete'];
        if (true) {
            $this->cudArticleService->delete($articleId);
        }

        return new TwigView('articleDeleted', ['articleId' => $articleId]);
    }
}

