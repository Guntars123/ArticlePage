<?php declare(strict_types=1);

use App\Controllers\ArticlesController;
use App\Controllers\UserController;

return
    [
        ['/', [ArticlesController::class, 'index']],
        ['/users', [UserController::class, 'index']],
        ['/article/{id:\d+}', [ArticlesController::class, 'show']],
        ['/user/{id:\d+}', [UserController::class, 'show']],
    ];
