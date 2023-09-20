<?php declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View implements ViewInterface
{
    private $twig;
    private string $template;

    public function __construct(string $templatePath)
    {
        $loader = new FilesystemLoader($templatePath);
        $this->twig = new Environment($loader);

        $this->parameters = []; //maybe breaks stuff remove later if its causing problems
    }

    public function addParameter($key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function display(string $template)
    {
        $this->template = $template;
        //$parameters = array_merge($this->parameters);
        //echo $this->twig->render($template, $parameters);
        echo $this->twig->render($template, $this->parameters);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}