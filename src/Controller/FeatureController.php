<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\Redirect;
use App\Core\Session;
use App\Core\View;

class FeatureController implements ControllerInterface
{
    private View $view;
    public Redirect $redirect;
    private Session $session;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->redirect = $container->get(Redirect::class);
        $this->session = $container->get(Session::class);
    }

    public function action(): View
    {
        if (!$this->session->loginStatus()) {
            $this->redirect->redirectTo('http://0.0.0.0:8000/?page=login');
        }

        $activeUser = $this->session->getUserName();

        $this->view->addParameter('activeUser', $activeUser);
        $this->view->setTemplate('feature.twig');

        return $this->view;
    }
}