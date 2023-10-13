<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\Redirect;
use App\Core\View;
use App\Model\UserRepository;

class LoginController implements ControllerInterface
{
    private View $view;
    private Redirect $redirect;
    private UserRepository $userRepository;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->redirect = $container->get(Redirect::class);
    }

    private function formInput(): array
    {

        $mailCheck = $_POST["mail"];
        $password = $_POST["password"];
        return ['mail' => $mailCheck, 'password' => $password];

    }

    public function action(): View
    {
        $this->view->setTemplate('login.twig');

        $credentials = $this->formInput();
        if ($credentials !== null) {
            $mailCheck = $credentials['mail'];
            $password = $credentials['password'];

            $userDTO = $this->userRepository->findByMail($mailCheck);

            if ($userDTO !== null) {
                $passwordCheck = $userDTO->password;
                if (password_verify($password, $passwordCheck)) {
                    $_SESSION["username"] = $userDTO->username;
                    $_SESSION["loginStatus"] = true;
                    $_SESSION["userID"] = $userDTO->userID;
                    $this->redirect->redirectTo('http://0.0.0.0:8000/?page=feature');
                }
            }
        }

        $this->view->addParameter('pageTitle', 'Login Page');

        return $this->view;
    }
}
