<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Account\AccountValidationException;
use App\Core\AccountValidation;
use App\Core\Container;
use App\Core\Redirect;
use App\Core\Session;
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
    public Redirect $redirect;
    private Session $session;
    private AccountValidation $accountValidation;
    private $success;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->redirect = $container->get(Redirect::class);
        $this->accountEntityManager = $container->get(AccountEntityManager::class);
        $this->accountRepository = $container->get(AccountRepository::class);
        $this->accountValidation = $container->get(AccountValidation::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->session = $container->get(Session::class);
    }

    public function action(): View
    {
        if (!$this->session->loginStatus()) {
            $this->redirect->redirectTo('http://0.0.0.0:8000/?page=login');
        }

        $activeUser = null;
        $balance = null;
        $loginStatus = false;

        if ($this->session->loginStatus()) {
            $loginStatus = $this->session->loginStatus();
            $activeUser = $this->session->getUserName();
            $balance = $this->accountRepository->calculateBalance($this->session->getUserID());
        }

        if (isset($_POST["transfer"])) {
            try {
                $receiver = $this->userRepository->findByMail($_POST["receiver"]);
                $validateThis = $this->getCorrectAmount($_POST["amount"]);
                $this->accountValidation->collectErrors($validateThis, $this->session->getUserID());
                $amount = $validateThis;

                if ($receiver !== null && $amount <= $balance) {
                    $date = date('Y-m-d');
                    $time = date('H:i:s');

                    $saveSender = new AccountDTO();
                    $saveSender->value = $amount * (-1);
                    $saveSender->userID = $this->session->getUserID();
                    $saveSender->transactionDate = $date;
                    $saveSender->transactionTime = $time;
                    $saveSender->purpose = 'Geldtransfer an ' . $receiver->username;
                    $this->accountEntityManager->saveDeposit($saveSender);

                    $saveReceiver = new AccountDTO();
                    $saveReceiver->value = $amount;
                    $saveReceiver->userID = $receiver->userID;
                    $saveReceiver->transactionDate = $date;
                    $saveReceiver->transactionTime = $time;
                    $saveReceiver->purpose = 'Zahlung erhalten von ' . $this->session->getUserName();
                    $this->accountEntityManager->saveDeposit($saveReceiver);

                    $this->success = "Die Transaktion wurde erfolgreich durchgeführt!";
                }
            } catch (AccountValidationException $e) {
                $this->view->addParameter('error', $e->getMessage());
            }
        }

        if (isset($_POST["logout"])) {
            $this->session->logout();
            header("Refresh:0");
        }

        $this->view->addParameter('activeUser', $activeUser);
        $this->view->addParameter('balance', $balance);
        $this->view->addParameter('loginStatus', $loginStatus);
        $this->view->addParameter('success', $this->success);

        $this->view->setTemplate('transaction.twig');

        return $this->view;
    }

    private
    function getCorrectAmount(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }
}