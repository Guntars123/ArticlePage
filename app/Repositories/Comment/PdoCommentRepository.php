<?php declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Models\Comment;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class PdoCommentRepository implements CommentRepository
{
    private Connection $connection;

    public function __construct()
    {

        $connectionParams = [
            'dbname' => $_ENV["DB_NAME"],
            'user' => $_ENV["USER"],
            'password' => $_ENV["PASSWORD"],
            'host' => $_ENV["HOST"],
            'driver' => 'pdo_mysql',
        ];
        $this->connection = DriverManager::getConnection($connectionParams);
    }
    public function getByArticleId(int $articleId): array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $comments = $queryBuilder->select('*')
                ->from('comments')
                ->where('article_id = :id')
                ->setParameter('id', $articleId)
                ->fetchAllAssociative();

            $commentCollection = [];

            foreach ($comments as $comment) {
                $commentCollection[] = $this->buildModel((object)$comment);
            }

            return $commentCollection;

        } catch (\Exception $e) {
            return [];
        }

    }

    public function save(Comment $comment): Comment
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('comments')
            ->values([
                'article_id' => ':articleId',
                'user_id' => ':userId',
                'body' => ':body',
            ])
            ->setParameter('articleId', $comment->getArticleId())
            ->setParameter('body', $comment->getBody())
            ->setParameter('userId', $comment->getUserId())
            ->executeStatement();

        $comment->setId((int) $this->connection->lastInsertId());


        return $comment;
    }

    private function buildModel(\stdClass $comment): Comment
    {
        return new Comment(
            (int) $comment->article_id,
            $comment->body,
            (int) $comment->user_id,

        );
    }
}