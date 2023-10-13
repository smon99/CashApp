<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\View;

class ErrorController implements ControllerInterface
{
    private View $view;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
    }

    public function action(): View
    {
        $viewParameters = [];

        $this->view->addParameter('parameters', $viewParameters);

        $this->view->setTemplate('unknown.twig');

        return $this->view;
    }
}