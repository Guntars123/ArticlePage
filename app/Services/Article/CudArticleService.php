<?php declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\Article\ArticleRepository;

class CudArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function create(string $title, string $content): int
    {
        return $this->articleRepository->create($title, $content);
    }

    public function edit(int $articleId, string $title, string $content): void
    {
        $this->articleRepository->edit($articleId, $title, $content);
    }

    public function delete(int $id): void
    {
        $this->articleRepository->delete($id);
    }
}