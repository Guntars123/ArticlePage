<?php declare(strict_types=1);

namespace App\Services\Article\Show;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Comment;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Comment\CommentRepository;

class ShowArticleService
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;

    public function __construct
    (
        ArticleRepository $articleRepository,
        UserRepository    $userRepository,
        CommentRepository $commentRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }

    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->articleRepository->getById($request->getArticleId());

        if ($article == null) {
            throw new ResourceNotFoundException("Article by id {$request->getArticleId()} not found");
        }

        $author = $this->userRepository->getById($article->getAuthorId());

        if ($author == null) {
            throw new ResourceNotFoundException("Article author by id {$article->getAuthorId()} not found");
        }

        $article->setAuthor($author);

        $comments = $this->commentRepository->getByArticleId($article->getId());

        foreach ($comments as $comment) {
            /** @var Comment $comment */
            $comment->setUser(
                $this->userRepository->getById($comment->getUserId())
            );
        }

        return new ShowArticleResponse($article, $comments);
    }
}
