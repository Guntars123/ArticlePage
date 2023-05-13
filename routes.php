<?php declare(strict_types=1);

use App\Controllers\ArticlesController;

return
    [
        ['/', [ArticlesController::class, 'index']],
        ['/article/{id:\d+}', [ArticlesController::class, 'article']],
        ['/user/{id:\d+}', [ArticlesController::class, 'user']],
    ];
