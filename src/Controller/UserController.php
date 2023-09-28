<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Redirect;
use App\Core\User\EMailValidator;
use App\Core\User\EmptyFieldValidator;
use App\Core\User\PasswordValidator;
use App\Core\User\UserDuplicationValidator;
use App\Core\UserValidation;
use App\Core\ViewInterface;
use App\Model\UserDTO;
use App\Model\UserEntityManager;
use App\Core\User\ValidationException;

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

    public function action(): void
    {
        $errors = [];
        $userCheck = null;
        $mailCheck = null;
        $passwordCheck = null;

        if (isset($_POST['register'])) {
            $userCheck = $_POST["username"];
            $mailCheck = $_POST["mail"];
            $passwordCheck = $_POST["password"];

            $validatorDTO = new UserDTO();
            $validatorDTO->user = $userCheck;
            $validatorDTO->eMail = $mailCheck;
            $validatorDTO->password = $passwordCheck;

            try {
                $validation = new UserValidation(
                    new EmptyFieldValidator(),
                    new UserDuplicationValidator(),
                    new PasswordValidator(),
                    new EMailValidator()
                );
                $validation->collectErrors($validatorDTO);

                $password = password_hash($passwordCheck, PASSWORD_DEFAULT);

                $userDTO = new UserDTO();
                $userDTO->user = $userCheck;
                $userDTO->eMail = $mailCheck;
                $userDTO->password = $password;

                $this->userEntityManager->save($userDTO);
                $this->redirect->redirectTo('http://0.0.0.0:8000/?input=login');
            } catch (ValidationException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->view->addParameter('error', implode(' ', $errors));
        }

        if ($userCheck !== null && $mailCheck !== null && $passwordCheck !== null) {
            $this->view->addParameter('tempUserName', $userCheck);
            $this->view->addParameter('tempMail', $mailCheck);
            $this->view->addParameter('tempPassword', $passwordCheck);
        }

        $this->view->display('user.twig');
    }
}
