<?php declare(strict_types=1);

namespace App\Models;

class Article
{
    private int $id;
    private int $authorId;
    private string $title;
    private string $body;
    private ?User $author = null;

    public function __construct(int $id, int $authorId, string $title, string $body)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->body = $body;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }
}



