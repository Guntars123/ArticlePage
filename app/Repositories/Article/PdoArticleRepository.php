<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class PdoArticleRepository implements ArticleRepository
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

    public function all(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $articles = $queryBuilder->select('*')
            ->from('articles')
            ->fetchAllAssociative();

        $articlesCollection = [];
        foreach ($articles as $article) {
            $articlesCollection[] = $this->buildModel($article);
        }
        return $articlesCollection;
    }

    public function getById(int $id): ?Article
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $article = $queryBuilder->select('*')
            ->from('articles')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        return $this->buildModel($article);
    }

    public function getByUserId(int $userId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $articles = $queryBuilder->select('*')
            ->from('articles')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->fetchAllAssociative();

        $articlesCollection = [];
        foreach ($articles as $article) {
            $articlesCollection[] = $this->buildModel($article);
        }
        return $articlesCollection;
    }

    public function create(string $title, string $content): int
    {
        $user_id = 1;
        $date = Carbon::now()->toDateTimeString();

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('articles')
            ->values([
                'user_id' => $queryBuilder->createNamedParameter($user_id),
                'title' => $queryBuilder->createNamedParameter($title),
                'content' => $queryBuilder->createNamedParameter($content),
                'date' => $queryBuilder->createNamedParameter($date)
            ]);

        $query = $queryBuilder->getSQL();
        $params = $queryBuilder->getParameters();

        $this->connection->executeQuery($query, $params);
        return (int)$this->connection->lastInsertId();
    }

    public function edit(int $articleId, string $newTitle, string $newContent): void
    {

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->update('articles')
            ->set('title', $queryBuilder->createNamedParameter($newTitle))
            ->set('content', $queryBuilder->createNamedParameter($newContent))
            ->where('id = :id')
            ->setParameter('id', $articleId);

        $query = $queryBuilder->getSQL();
        $params = $queryBuilder->getParameters();

        $this->connection->executeQuery($query, $params);
    }

    public function delete(int $articleId): void
    {

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->delete('articles')
            ->where('id = :id')
            ->setParameter('id', $articleId);

        $query = $queryBuilder->getSQL();
        $params = $queryBuilder->getParameters();

        $this->connection->executeQuery($query, $params);
    }

    private function buildModel(array $articles): Article
    {
        return new Article
        (
            (int)$articles['id'],
            (int)$articles['user_id'],
            $articles['title'],
            $articles['content'],
            $articles['date']
        );
    }
}