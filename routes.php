<?php declare(strict_types=1);

use App\Controllers\ArticlesController;
use App\Controllers\CommentController;
use App\Controllers\UserController;

return
    [
        //Users
        ['GET', '/', [ArticlesController::class, 'index']],
        ['GET', '/users', [UserController::class, 'index']],
        ['GET', '/users/{id:\d+}', [UserController::class, 'show']],
        ['GET', '/register', [UserController::class, 'register']],
        ['POST', '/register', [UserController::class, 'store']],
        ['GET', '/login', [UserController::class, 'loginShow']],
        ['POST', '/login', [UserController::class, 'login']],
        ['GET', '/logout', [UserController::class, 'logout']],

        //Articles
        ['GET', '/articles', [ArticlesController::class, 'index']],
        ['GET', '/articles/{id:\d+}', [ArticlesController::class, 'show']],
        ['GET', '/articles/create', [ArticlesController::class, 'create']],
        ['POST', '/articles', [ArticlesController::class, 'store']],
        ['GET', '/articles/edit/{id:\d+}', [ArticlesController::class, 'edit']],
        ['POST', '/articles/{id:\d+}', [ArticlesController::class, 'update']],
        ['POST', '/articles/delete', [ArticlesController::class, 'delete']],

        //Comments
        ['POST', '/comment/{id:\d+}', [CommentController::class, 'create']],
    ];
