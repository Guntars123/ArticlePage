<?php declare(strict_types=1);

namespace App\Controllers;


use App\Core\NotFoundView;
use App\Core\TwigView;
use App\Core\View;
use App\Exceptions\ResourceNotFoundException;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UserController
{
    public function index(): View
    {
        $service = new IndexUserService();
        $users = $service->execute();

        return new TwigView("users", ['users' => $users]);
    }

    public function show(array $vars): View
    {
        try {
            $userId = (int)$vars["id"];
            $service = new ShowUserService();

            $response = $service->execute(new ShowUserRequest($userId));

            return new TwigView("user", [
                'user' => $response->getUser(),
                'articles' => $response->getArticles()
            ]);

        } catch (ResourceNotFoundException $exception) {
            return new NotFoundView("notFound");
        }
    }
}


