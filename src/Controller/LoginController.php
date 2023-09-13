<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\UserRepository;

class LoginController
{
    private $view;

    public function __construct(ViewInterface $view, private Redirect $redirect)
    {
        $this->view = $view;
    }

    public function userLogin(): void
    {
        if (isset($_POST['login'])) {
            $mailCheck = $_POST["mail"];
            $password = $_POST["password"];

            $userRepository = new UserRepository();
            $request = $userRepository->findByMail($mailCheck);

            if ($request["eMail"] === $mailCheck) {
                if ($request !== null) {
                    $passwordCheck = $request["password"];
                    if (password_verify($password, $passwordCheck)) {
                        $_SESSION["username"] = $request["user"];
                        $_SESSION["loginStatus"] = true;
                        echo "logged in as ", $request["user"];
                    } else {
                        $_SESSION["loginStatus"] = false;
                        echo "nice try";
                    }
                }
                if ($_SESSION["loginStatus"] === true) {
                    $this->redirect->redirectTo('http://0.0.0.0:8000/?input=deposit');
                }
            }
        }

        $this->view->addParameter('pageTitle', 'Login Page');

        $this->view->display('login.twig');
    }
}