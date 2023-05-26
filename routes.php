<?php declare(strict_types=1);

use App\Controllers\ArticlesController;
use App\Controllers\UserController;

return
    [
        ['GET', '/', [ArticlesController::class, 'index']],
        ['GET', '/users', [UserController::class, 'index']],
        ['GET', '/article/{id:\d+}', [ArticlesController::class, 'show']],
        ['GET', '/user/{id:\d+}', [UserController::class, 'show']],
        ['GET', '/add', [ArticlesController::class, 'createView']],
        ['POST', '/add', [ArticlesController::class, 'create']],
        ['GET', '/article/edit/{id:\d+}', [ArticlesController::class, 'editView']],
        ['POST', '/article/edit/{id:\d+}', [ArticlesController::class, 'edit']],
        ['POST', '/article/delete', [ArticlesController::class, 'delete']]
    ];
