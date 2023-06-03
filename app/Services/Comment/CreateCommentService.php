<?php declare(strict_types=1);

namespace App\Services\Comment;

use App\Models\Comment;
use App\Repositories\Comment\CommentRepository;

class CreateCommentService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function execute(CreateCommentRequest $request): CreateCommentResponse
    {
        $comment = new Comment(
            $request->getArticleId(),
            $request->getBody(),
            $request->getUserId(),
        );

        $this->commentRepository->save($comment);

        return new CreateCommentResponse($comment);
    }
}