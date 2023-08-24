<?php declare(strict_types=1);

namespace Controller;

use Model\UserRepository;
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

    public function userLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    header("Location: http://0.0.0.0:8000/?input=deposit");
                    exit();
                }
            }
        }
        echo $this->twig->render('login.twig');
    }
}