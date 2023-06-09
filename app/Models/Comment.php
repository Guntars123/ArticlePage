<?php declare(strict_types=1);

namespace App\Models;

class Comment
{
    private int $articleId;
    private string $body;
    private int $userId;
    private ?User $user = null;
    private ?int $id = null;

    public function __construct(
        int    $articleId,
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}