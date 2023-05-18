<?php declare(strict_types=1);

namespace App\Commands;
class ConsoleRouter
{
    public static function response(array $argv): void
    {
        $resource = $argv[1] ?? null;
        $id = $argv[2] ?? null;

        switch ($resource) {
            case 'articles':
                if ($id != null) {
                    (new ConsoleArticlesController())->show((int)$id);
                } else {
                    (new ConsoleArticlesController())->index();
                }
                break;
            case 'user':
                if ($id != null) {
                    (new ConsoleUserController())->show((int)$id);
                } else {
                    (new ConsoleUserController())->index();
                }
                break;
            default:
                echo 'Invalid resource. Please try again!';
                break;
        }
    }
}
