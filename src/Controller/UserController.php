<?php declare(strict_types=1);

namespace Controller;

use Core\ViewInterface;
use Model\UserEntityManager;
use Model\UserRepository;

class UserController
{
    private $view;

    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
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

    public function registration(): ?array
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
                $userCheck = $_POST["username"];
                $mailCheck = $_POST["mail"];
                $passwordCheck = $_POST["password"];

                $userRepository = new UserRepository();
                $mailRequest = $userRepository->findByMail($mailCheck);
                $userRequest = $userRepository->findByUsername($userCheck);

                if ($mailRequest !== null) {
                    $error = "Fehler eMail bereits vergeben";
                }

                if ($userRequest !== null) {
                    $error = "Fehler Name bereits vergeben";
                }

                if (filter_var($mailCheck, FILTER_VALIDATE_EMAIL)) {
                    $eMail = $mailCheck;
                }
                if (!isset($eMail)) {
                    $error = "Bitte gültige eMail eingeben!";
                }

                if ($this->validatePassword($passwordCheck)) {
                    $password = password_hash($passwordCheck, PASSWORD_DEFAULT);

                    if (!isset($error)) {
                        $user = [
                            "user" => $userCheck,
                            "eMail" => $mailCheck,
                            "password" => $password,
                        ];

                        $this->view->display('user.twig');

                        $path = '/../Model/user.json';
                        $userEntityManager = new UserEntityManager();
                        $save = $userEntityManager->save($user, $path);
                        return $user;
                    }
                } else {
                    $error = "Passwort Anforderungen nicht erfüllt (find out yourself)";
                }
            }
        }
        if (isset($error)) {
            $this->view->addParameter('error', $error);
        }
        if (null !== ($tempUserName && $tempMail && $tempPassword)) {
            $this->view->addParameter('tempUserName', $tempUserName);
            $this->view->addParameter('tempMail', $tempMail);
            $this->view->addParameter('tempPassword', $tempPassword);
        }
        $this->view->display('user.twig');

        return null;
    }
}