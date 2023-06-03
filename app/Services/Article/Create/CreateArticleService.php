<?php declare(strict_types=1);

namespace App\Services\Article\Create;


use App\Models\Article;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\User\UserRepository;


class CreateArticleService
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;

    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;

    }

    public function execute(CreateArticleRequest $request): CreateArticleResponse
    {

        $article = new Article(
            (int) $this->userRepository->getByEmail($_SESSION['user']['email'])->getId(),
            $request->getTitle(),
            $request->getContent(),

        );

        $this->articleRepository->save($article);

        return new CreateArticleResponse($article);
    }
}
