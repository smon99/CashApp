<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\UserDTO;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use App\Model\UserMapper;

class UserController
{
    private $view;

    public function __construct(
        ViewInterface             $view,
        private Redirect          $redirect,
        private UserRepository    $userRepository,
        private UserEntityManager $userEntityManager,
        private UserMapper        $userMapper
    )
    {
        $this->view = $view;
    }

    public function validatePassword(string $passwordCheck): bool
    {
        $uppercase = preg_match('@[A-Z]@', $passwordCheck);
        $lowercase = preg_match('@[a-z]@', $passwordCheck);
        $number = preg_match('@[0-9]@', $passwordCheck);
        $specialChar = preg_match('@[^\w]@', $passwordCheck);
        $minLength = 6;

        return $uppercase && $lowercase && $number && $specialChar && strlen($passwordCheck) >= $minLength;
    }

    public function registration(): void
    {
        $error = null;
        $tempUserName = null;
        $tempMail = null;
        $tempPassword = null;

        if (isset($_POST['register'])) {
            $userCheck = $_POST["username"];
            $mailCheck = $_POST["mail"];
            $passwordCheck = $_POST["password"];

            if (empty($userCheck) || empty($mailCheck) || empty($passwordCheck)) {
                $error = "Alle Felder müssen ausgefüllt sein!";

                $tempUserName = $userCheck;
                $tempMail = $mailCheck;
                $tempPassword = $passwordCheck;
            } else {
                $mailRequest = $this->userRepository->findByMail($mailCheck);
                $userRequest = $this->userRepository->findByUsername($userCheck);

                if ($mailRequest !== null) {
                    $error = "Fehler eMail bereits vergeben";
                }

                if ($userRequest !== null) {
                    $error = "Fehler Name bereits vergeben";
                }

                if (!filter_var($mailCheck, FILTER_VALIDATE_EMAIL)) {
                    $error = "Bitte gültige eMail eingeben!";
                }

                if ($this->validatePassword($passwordCheck)) {
                    $password = password_hash($passwordCheck, PASSWORD_DEFAULT);

                    if (!isset($error)) {
                        $userDTO = new UserDTO();
                        $userDTO->user = $userCheck;
                        $userDTO->eMail = $mailCheck;
                        $userDTO->password = $password;

                        $this->userEntityManager->save($userDTO);

                        $this->redirect->redirectTo('http://0.0.0.0:8000/?input=login');
                    }
                } else {
                    $error = "Passwort Anforderungen nicht erfüllt";
                }
            }
        }

        if (isset($error)) {
            $this->view->addParameter('error', $error);
        }

        if ($tempUserName !== null && $tempMail !== null && $tempPassword !== null) {
            $this->view->addParameter('tempUserName', $tempUserName);
            $this->view->addParameter('tempMail', $tempMail);
            $this->view->addParameter('tempPassword', $tempPassword);
        }

        $this->view->display('user.twig');
    }
}
