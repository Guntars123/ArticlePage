<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;

class Article
{
    private int $authorId;
    private string $title;
    private string $content;
    private string $createdAt;
    private ?User $author = null;
    private ?int $id;

    public function __construct
    (
        int    $authorId,
        string $title,
        string $content,
        string $createdAt = null,
        int    $id = null
    )
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt ?? Carbon::now()->format(DateTimeInterface::ATOM);

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }


    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    public function update(array $attributes): void
    {
        foreach ($attributes as $attribute => $value) {
            $this->{$attribute} = $value;
        }
    }
}



