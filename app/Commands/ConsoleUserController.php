<?php declare(strict_types=1);

namespace App\Commands;

use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class ConsoleUserController
{
    public function index(): void
    {
        $service = new IndexUserService();
        $users = $service->execute();

        (new ConsoleRenderer())->renderUsers($users);
    }

    public function show(int $id): void
    {
        $userId = $id;
        $service = new ShowUserService();
        $response = $service->execute(new ShowUserRequest($userId));

        $user = $response->getUser();
        $articles = $response->getArticles();

        (new ConsoleRenderer())->renderSingleUser($user, $articles);
    }
}