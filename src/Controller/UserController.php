<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Redirect;
use App\Core\User\EMailValidator;
use App\Core\User\PasswordValidator;
use App\Core\User\UserDuplicationValidator;
use App\Core\UserValidation;
use App\Core\ViewInterface;
use App\Model\UserDTO;
use App\Model\UserEntityManager;

class UserController
{
    private $view;

    public function __construct(
        ViewInterface             $view,
        private Redirect          $redirect,
        private UserEntityManager $userEntityManager,
    )
    {
        $this->view = $view;
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
            }

            if (!empty($userCheck) || !empty($mailCheck) || !empty($passwordCheck)) {
                $validatorDTO = new UserDTO();
                $validatorDTO->user = $userCheck;
                $validatorDTO->eMail = $mailCheck;
                $validatorDTO->password = $passwordCheck;

                $validation = new UserValidation(new UserDuplicationValidator(), new PasswordValidator(), new EMailValidator());
                $errors = $validation->collectErrors($validatorDTO);

                if ($errors === true) {
                    $password = password_hash($passwordCheck, PASSWORD_DEFAULT);

                    $userDTO = new UserDTO();
                    $userDTO->user = $userCheck;
                    $userDTO->eMail = $mailCheck;
                    $userDTO->password = $password;

                    $this->userEntityManager->save($userDTO);

                    $this->redirect->redirectTo('http://0.0.0.0:8000/?input=login');
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