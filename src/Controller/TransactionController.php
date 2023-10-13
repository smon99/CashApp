<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\Redirect;
use App\Core\View;
use App\Model\AccountDTO;
use App\Model\AccountEntityManager;
use App\Model\AccountRepository;
use App\Model\UserRepository;

class TransactionController implements ControllerInterface
{
    private View $view;
    private AccountEntityManager $accountEntityManager;
    private AccountRepository $accountRepository;
    private UserRepository $userRepository;
    private Redirect $redirect;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->redirect = $container->get(Redirect::class);
        $this->accountEntityManager = $container->get(AccountEntityManager::class);
        $this->accountRepository = $container->get(AccountRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
    }

    public function action(): void
    {
        if (!isset($_SESSION["loginStatus"])) {
            $this->redirect->redirectTo('http://0.0.0.0:8000/?page=login');
        }

        $activeUser = null;
        $balance = null;
        $loginStatus = false;

        if (isset($_SESSION["loginStatus"])) {
            $loginStatus = $_SESSION["loginStatus"];
            $activeUser = $_SESSION["username"];
            $balance = $this->accountRepository->calculateBalance($_SESSION["userID"]);
        }

        if (isset($_POST["transfer"])) {
            $receiver = $this->userRepository->findByMail($_POST["receiver"]);
            $amount = $this->getCorrectAmount($_POST["amount"]);

            if ($receiver !== null && $amount <= $balance) {
                $date = date('Y-m-d');
                $time = date('H:i:s');

                $saveSender = new AccountDTO();
                $saveSender->value = $amount * (-1);
                $saveSender->userID = $_SESSION["userID"];
                $saveSender->transactionDate = $date;
                $saveSender->transactionTime = $time;
                $saveSender->purpose = 'Geldtransfer an ' . $receiver->username;
                $this->accountEntityManager->saveDeposit($saveSender);

                $saveReceiver = new AccountDTO();
                $saveReceiver->value = $amount;
                $saveReceiver->userID = $receiver->userID;
                $saveReceiver->transactionDate = $date;
                $saveReceiver->transactionTime = $time;
                $saveReceiver->purpose = 'Zahlung erhalten von ' . $_SESSION["username"];
                $this->accountEntityManager->saveDeposit($saveReceiver);

                header("Refresh:0");
            }
        }

        $this->view->addParameter('activeUser', $activeUser);
        $this->view->addParameter('balance', $balance);
        $this->view->addParameter('loginStatus', $loginStatus);

        $this->view->setTemplate('transaction.twig');
    }

    private function getCorrectAmount(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }
}