<?php declare(strict_types=1);

namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View implements ViewInterface
{
    private $twig;

    public function __construct(string $templatePath)
    {
        $loader = new FilesystemLoader($templatePath);
        $this->twig = new Environment($loader);
    }

    public function addParameter($key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function display(string $template)
    {
        $parameters = array_merge($this->parameters);
        echo $this->twig->render($template, $parameters);
    }
}