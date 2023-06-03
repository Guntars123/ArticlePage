<?php declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Renderer
{
    private Environment $twig;

    public function __construct(string $basePath)
    {
        $loader = new FilesystemLoader($basePath);
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);
    }

    public function render(View $view): string
    {
        return $this->twig->render($view->getTemplate() . '.twig', $view->getData());
    }
}