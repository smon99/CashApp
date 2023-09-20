<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\UserRepository;

class LoginController
{
    private $view;

    public function __construct(ViewInterface $view, private Redirect $redirect, private UserRepository $userRepository)
    {
        $this->view = $view;
    }

    public function userLogin(): void
    {
        if (isset($_POST['login'])) {
            $mailCheck = $_POST["mail"];
            $password = $_POST["password"];

            $userDTO = $this->userRepository->findByMail($mailCheck);

            if ($userDTO !== null) {
                $passwordCheck = $userDTO->password;
                if (password_verify($password, $passwordCheck)) {
                    $_SESSION["username"] = $userDTO->user;
                    $_SESSION["loginStatus"] = true;
                    echo "Logged in as ", $userDTO->user;
                } else {
                    $_SESSION["loginStatus"] = false;
                    echo "Nice try";
                }
            }

            if ($_SESSION["loginStatus"] === true) {
                $this->redirect->redirectTo('http://0.0.0.0:8000/?input=deposit');
            }
        }

        $this->view->addParameter('pageTitle', 'Login Page');

        $this->view->display('login.twig');
    }
}
