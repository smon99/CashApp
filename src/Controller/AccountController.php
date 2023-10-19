<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\Redirect;
use App\Core\View;
use App\Model\AccountDTO;
use App\Model\AccountRepository;
use App\Core\AccountValidation;
use App\Core\Account\AccountValidationException;
use App\Model\AccountEntityManager;

class AccountController implements ControllerInterface
{
    private View $view;
    private AccountRepository $accountRepository;
    private AccountEntityManager $entityManager;
    private AccountValidation $validator;
    public Redirect $redirect;
    private $success;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->accountRepository = $container->get(AccountRepository::class);
        $this->entityManager = $container->get(AccountEntityManager::class);
        $this->validator = $container->get(AccountValidation::class);
        $this->redirect = $container->get(Redirect::class);
    }

    public function action(): View
    {
        if (!isset($_SESSION["loginStatus"])) {
            $this->redirect->redirectTo('http://0.0.0.0:8000/?page=login');
        }

        $activeUser = null;
        $loginStatus = false;
        $balance = null;

        $input = $_POST["amount"] ?? null;

        if ($input !== null) {
            try {
                $validateThis = $this->getCorrectAmount($input);

                $this->validator->collectErrors($validateThis, $_SESSION["userID"]);

                $amount = $validateThis;

                $date = date('Y-m-d');
                $time = date('H:i:s');

                $saveData = new AccountDTO();
                $saveData->value = $amount;
                $saveData->userID = $_SESSION["userID"];
                $saveData->transactionDate = $date;
                $saveData->transactionTime = $time;
                $saveData->purpose = 'deposit';
                $this->entityManager->saveDeposit($saveData);
                $this->success = "Die Transaktion wurde erfolgreich gespeichert!";
            } catch (AccountValidationException $e) {
                $this->view->addParameter('error', $e->getMessage());
            }
        }

        if (isset($_POST["logout"])) {
            session_destroy();
            $this->redirect->redirectTo('http://0.0.0.0:8000/?page=login');
        }

        if (isset($_SESSION["loginStatus"])) {
            $loginStatus = $_SESSION["loginStatus"];
            $activeUser = $_SESSION["username"];
            $balance = $this->accountRepository->calculateBalance($_SESSION["userID"]);
        }

        $this->view->addParameter('balance', $balance);
        $this->view->addParameter('loginStatus', $loginStatus);
        $this->view->addParameter('activeUser', $activeUser);
        $this->view->addParameter('success', $this->success);

        $this->view->setTemplate('deposit.twig');

        return $this->view;
    }

    private function getCorrectAmount(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }
}
