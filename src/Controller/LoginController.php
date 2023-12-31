<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\Redirect;
use App\Core\Session;
use App\Core\View;
use App\Model\UserRepository;

class LoginController implements ControllerInterface
{
    private View $view;
    public Redirect $redirect;
    private UserRepository $userRepository;
    private Session $session;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->redirect = $container->get(Redirect::class);
        $this->session = $container->get(Session::class);
    }

    private function formInput(): array
    {
        $mailCheck = $_POST['mail'];
        $password = $_POST['password'];
        return ['mail' => $mailCheck, 'password' => $password];
    }

    public function action(): View
    {
        $credentials = null;

        if (isset($_POST['login'])) {
            $credentials = $this->formInput();
        }

        if ($credentials !== null) {
            $userDTO = $this->userRepository->findByMail($credentials['mail']);

            if ($userDTO !== null) {
                $this->session->loginUser($userDTO, $credentials['password']);
                $this->redirect->redirectTo('http://0.0.0.0:8000/?page=feature');
            }
        }

        $this->view->addParameter('pageTitle', 'Login Page');
        $this->view->setTemplate('login.twig');
        return $this->view;
    }
}
