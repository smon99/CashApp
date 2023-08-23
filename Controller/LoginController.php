<?php declare(strict_types=1);

namespace Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LoginController
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader);

    }

    public function userSearch()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $mail = $_POST["mail"];
            $password = $_POST["password"];

            $user = json_decode(file_get_contents(__DIR__ . '/../Model/user.json'), true);

            if (!empty($user)) {
                foreach ($user as $userCheck) {
                    if ($userCheck["eMail"] === $mail) {
                        $passwordCheck = $userCheck["password"];

                        if (password_verify($password, $passwordCheck)) {
                            $_SESSION["username"] = $userCheck["user"];
                            $_SESSION["loginStatus"] = true;
                            echo "logged in as ", $userCheck["user"];

                        } else {
                            $_SESSION["loginStatus"] = false;
                            echo "nice try";
                        }
                    }
                }
            }
            if ($_SESSION["loginStatus"] === true) {
                header("Location: http://0.0.0.0:8000/?input=deposit");
                exit();
            }
        }
        echo $this->twig->render('login.twig');
    }
}