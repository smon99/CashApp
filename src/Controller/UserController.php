<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\Redirect;
use App\Core\User\EMailValidator;
use App\Core\User\EmptyFieldValidator;
use App\Core\User\PasswordValidator;
use App\Core\User\UserDuplicationValidator;
use App\Core\UserValidation;
use App\Core\View;
use App\Model\UserDTO;
use App\Model\UserEntityManager;
use App\Core\User\UserValidationException;

class UserController implements ControllerInterface
{
    private View $view;
    public Redirect $redirect;
    private UserEntityManager $userEntityManager;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->redirect = $container->get(Redirect::class);
        $this->userEntityManager = $container->get(UserEntityManager::class);
    }

    public function action(): View
    {
        $errors = [];
        $userCheck = '';
        $mailCheck = '';
        $passwordCheck = '';

        if (isset($_POST['register'])) {
            $userCheck = $_POST['username'];
            $mailCheck = $_POST['email'];
            $passwordCheck = $_POST['password'];

            $validatorDTO = new UserDTO();
            $validatorDTO->username = $userCheck;
            $validatorDTO->email = $mailCheck;
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
                $userDTO->username = $userCheck;
                $userDTO->email = $mailCheck;
                $userDTO->password = $password;

                $this->userEntityManager->save($userDTO);
                $this->redirect->redirectTo('http://0.0.0.0:8000/?page=login');
            } catch (UserValidationException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->view->addParameter('error', implode(' ', $errors));
        }

        $this->view->addParameter('tempUserName', $userCheck);
        $this->view->addParameter('tempMail', $mailCheck);
        $this->view->addParameter('tempPassword', $passwordCheck);

        $this->view->setTemplate('user.twig');

        return $this->view;
    }
}
