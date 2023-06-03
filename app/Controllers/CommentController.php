<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\Comment\CreateCommentRequest;
use App\Services\Comment\CreateCommentService;

class CommentController
{
    private CreateCommentService $createCommentService;

    public function __construct(CreateCommentService $createCommentService)
    {
        $this->createCommentService = $createCommentService;
    }

    public function create(array $vars): void
    {
        $body = $_POST['body'];
        $articleId = (int)$vars['id'];

        $userId = $_SESSION['user']['id'];

        $comment = $this->createCommentService->execute(
            new CreateCommentRequest(
                $articleId,
                $body,
                $userId,
            ));

        header('Location: /articles/' . $comment->getComment()->getArticleId());
        exit();
    }
}
