<?php declare(strict_types=1);

namespace App\Core;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\PdoArticleRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\JsonPlaceholderCommentRepository;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    public static function response(array $routes): ?View
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions([
            ArticleRepository::class => new PdoArticleRepository(),
            UserRepository::class => new JsonPlaceholderUserRepository(),
            CommentRepository::class => new JsonPlaceholderCommentRepository()
        ]);

        $container = $builder->build();


        $dispatcher = simpleDispatcher(function (RouteCollector $router) use ($routes) {
            foreach ($routes as $route) {
                [$method, $path, $handler] = $route;
                $router->addRoute($method, $path, $handler);
            }
        });

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($requestMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return null;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                return null;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [$controllerName, $methodName] = $handler;
                $controller = $container->get($controllerName);

                return $controller->{$methodName}($vars);
        }
        return null;
    }
}
