<?php declare(strict_types=1);

namespace Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserController
{
    private $loader;
    private $twig;
    private $user;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($this->loader);

        if (!file_exists(__DIR__ . '/../Model/user.json')) {
            file_put_contents(__DIR__ . '/../Model/user.json', json_encode([]));
        }

        $this->user = json_decode(file_get_contents(__DIR__ . '/../Model/user.json'), true);
    }

    private function validatePassword($passwordCheck): bool
    {
        $uppercase = preg_match('@[A-Z]@', $passwordCheck);
        $lowercase = preg_match('@[a-z]@', $passwordCheck);
        $number = preg_match('@[0-9]@', $passwordCheck);
        $specialChar = preg_match('@[^\w]@', $passwordCheck);
        $minLength = 6;

        return $uppercase && $lowercase && $number && $specialChar && strlen($passwordCheck) >= $minLength;
    }

    private function showError($error)
    {
        echo $error;
    }

    private function redirectToLogin()
    {
        file_put_contents(__DIR__ . '/../Model/user.json', json_encode($this->user, JSON_PRETTY_PRINT));
        header("Location: http://0.0.0.0:8000/Controller/TestLoginController.php");
        exit();
    }

    public function handleRegistration()
    {
        $error = null;
        $tempUserName = null;
        $tempMail = null;
        $tempPassword = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST["username"]) || empty($_POST["mail"]) || empty($_POST["password"])) {
                $error = "Alle Felder müssen ausgefüllt sein!";

                if (isset($_POST["username"])) {
                    $tempUserName = $_POST["username"];
                }
                if (isset($_POST["mail"])) {
                    $tempMail = $_POST["mail"];
                }
                if (isset($_POST["password"])) {
                    $tempPassword = $_POST["password"];
                }
            } elseif (isset($_POST["username"], $_POST["mail"], $_POST["password"])) {
                $userName = $_POST["username"];
                $eMailCheck = $_POST["mail"];
                $passwordCheck = $_POST["password"];

                if (!empty($this->user)) {
                    foreach ($this->user as $userData) {
                        if ($userData["eMail"] === $eMailCheck) {
                            $error = "Fehler eMail bereits vergeben";
                            break;
                        }
                        if ($userData["user"] === $userName) {
                            $error = "Fehler Name bereits vergeben";
                            break;
                        }
                    }
                }

                if (filter_var($eMailCheck, FILTER_VALIDATE_EMAIL)) {
                    $eMail = $eMailCheck;
                }
                if (!isset($eMail)) {
                    $error = "Bitte gültige eMail eingeben!";
                }

                if ($this->validatePassword($passwordCheck)) {
                    $password = password_hash($passwordCheck, PASSWORD_DEFAULT);

                    if (!isset($error)) {
                        $newUser = [
                            "user" => $userName,
                            "eMail" => $eMail,
                            "password" => $password,
                        ];

                        $this->user[] = $newUser;
                    }
                } else {
                    $error = "Passwort Anforderungen nicht erfüllt (find out yourself)";
                }
            }

            if (!isset($error)) {
                $this->redirectToLogin();
            } else {
                $this->showError($error);
            }
        }

        echo $this->twig->render('user.twig', [
            'tempUserName' => $tempUserName,
            'tempMail' => $tempMail,
            'tempPassword' => $tempPassword,
        ]);
    }
}