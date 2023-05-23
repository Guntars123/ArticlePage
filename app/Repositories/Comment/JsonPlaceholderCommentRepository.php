<?php declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Cache;
use App\Models\Comment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class JsonPlaceholderCommentRepository implements CommentRepository
{
    private Client $client;
    private const BASE_URI = 'https://jsonplaceholder.typicode.com/';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getByArticleId(int $articleId): array
    {
        if (!Cache::has('comments' . $articleId)) {
            $commentsResponse = $this->apiRequest(self::BASE_URI . "comments?postId=" . $articleId);
            Cache::remember('comments' . $articleId, $commentsResponse);
        } else {
            $commentsResponse = Cache::get('comments' . $articleId);
        }
        if (!empty($commentsResponse)) {
            $responseData = json_decode($commentsResponse);
            $comments = [];
            foreach ($responseData as $comment) {
                $comments[] = $this->buildModel($comment);
            }
            return $comments;
        }
        return [];
    }

    private function buildModel(stdClass $comment): Comment
    {
        return new Comment
        (
            $comment->postId,
            $comment->id,
            $comment->name,
            $comment->email,
            $comment->body,
        );
    }

    private function apiRequest(string $url): string
    {
        try {
            $response = $this->client->get($url);
            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            return "";
        }
    }
}
