<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
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

    public function save(Article $article): void
    {
        if ($article->getId() === null) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->insert('articles')
                ->values([
                    'user_id' => $queryBuilder->createNamedParameter($article->getAuthorId()),
                    'title' => $queryBuilder->createNamedParameter($article->getTitle()),
                    'content' => $queryBuilder->createNamedParameter($article->getContent()),
                    'created_at' => $queryBuilder->createNamedParameter($article->getCreatedAt())
                ]);

            $query = $queryBuilder->getSQL();
            $params = $queryBuilder->getParameters();

            $this->connection->executeQuery($query, $params);
            $article->setId((int)$this->connection->lastInsertId());

        } else {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('articles')
                ->set('title', $queryBuilder->createNamedParameter($article->getTitle()))
                ->set('content', $queryBuilder->createNamedParameter($article->getContent()))
                ->where('id = :id')
                ->setParameter('id', $article->getId());

            $query = $queryBuilder->getSQL();
            $params = $queryBuilder->getParameters();

            $this->connection->executeQuery($query, $params);
        }
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
            (int)$articles['user_id'],
            $articles['title'],
            $articles['content'],
            $articles['created_at'],
            (int)$articles['id'],
        );
    }
}