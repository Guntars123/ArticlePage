<?php declare(strict_types=1);

namespace App\Services\Comment;

use App\Models\User;

class CreateCommentRequest
{
    private int $articleId;
    private string $body;
    private int $userId;

    public function __construct(
        int $articleId,
        string $body,
        int $userId
    )
    {
        $this->articleId = $articleId;
        $this->body = $body;
        $this->userId = $userId;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
