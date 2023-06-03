<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class PdoUserRepository implements UserRepository
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
        $users = $queryBuilder->select('*')
            ->from('users')
            ->fetchAllAssociative();

        $usersCollection = [];
        foreach ($users as $user) {
            $usersCollection[] = $this->buildModel($user);
        }
        return $usersCollection;
    }

    public function getById(int $id): ?User
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $user = $queryBuilder->select('*')
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->fetchAssociative();

        return $this->buildModel($user);
    }

    public function getByEmail(string $email): ?User
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $user = $queryBuilder->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->fetchAssociative();

        return $this->buildModel($user);
    }

    public function checkEmail(string $email): bool
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $user = $queryBuilder->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeStatement();

        if ($user > 0) {
            return true;
        }
        return false;
    }

    public function save(User $user): void
    {
        if ($user->getId() === null) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->insert('users')
                ->values([
                    'user_name' => $queryBuilder->createNamedParameter($user->getUserName()),
                    'email' => $queryBuilder->createNamedParameter($user->getEmail()),
                    'password' => $queryBuilder->createNamedParameter($user->getPassword()),
                ]);

            $query = $queryBuilder->getSQL();
            $params = $queryBuilder->getParameters();

            $this->connection->executeQuery($query, $params);
            $user->setId((int)$this->connection->lastInsertId());

        } else {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('users')
                ->set('user_name', $queryBuilder->createNamedParameter($user->getUserName()))
                ->set('password', $queryBuilder->createNamedParameter($user->getPassword()))
                ->set('email', $queryBuilder->createNamedParameter($user->getEmail()))
                ->where('id = :id')
                ->setParameter('id', $user->getId());

            $query = $queryBuilder->getSQL();
            $params = $queryBuilder->getParameters();

            $this->connection->executeQuery($query, $params);
        }
    }

    public function login(string $email, string $password): ?User
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $user = $queryBuilder->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)->fetchAssociative();

        if ($user == null || !password_verify($password, $user['password'])) {
            return null;
        }
        return $this->buildModel($user);
    }

    public function delete(int $userId): void
    {

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->delete('users')
            ->where('id = :id')
            ->setParameter('id', $userId);

        $query = $queryBuilder->getSQL();
        $params = $queryBuilder->getParameters();

        $this->connection->executeQuery($query, $params);
    }

    private function buildModel(array $users): User
    {
        return new User
        (
            $users['user_name'],
            $users['email'],
            $users['password'],
            (int) $users['id'],
        );
    }
}
