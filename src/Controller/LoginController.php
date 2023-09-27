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

    public function formInput(): ?array
    {
        if (isset($_POST['login'])) {
            $mailCheck = $_POST["mail"];
            $password = $_POST["password"];
            return [$mailCheck, $password];
        }
        return null;
    }

    public function action(): void
    {
        $credentials = $this->formInput();
        if ($credentials !== null) {
            $mailCheck = $credentials[0];
            $password = $credentials[1];

            $userDTO = $this->userRepository->findByMail($mailCheck);

            if ($userDTO !== null) {
                $passwordCheck = $userDTO->password;
                if (password_verify($password, $passwordCheck)) {
                    $_SESSION["username"] = $userDTO->user;
                    $_SESSION["loginStatus"] = true;
                    echo "Logged in as ", $userDTO->user;
                    $this->redirect->redirectTo('http://0.0.0.0:8000/?input=deposit');
                }
                echo "Nice try";
            }
        }
        $this->view->addParameter('pageTitle', 'Login Page');
        $this->view->display('login.twig');
    }
}