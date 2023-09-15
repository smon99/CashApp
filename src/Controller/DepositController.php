<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\AccountDTO;
use App\Model\AccountRepository;
use App\Core\ViewInterface;
use App\Core\AccountValidation;
use App\Model\AccountEntityManager;

class DepositController
{
    private AccountValidation $validator;
    private ViewInterface $view;
    private AccountRepository $repository;
    private AccountEntityManager $entityManager;
    private $error;
    private $success;

    public function __construct(
        ViewInterface        $view,
        AccountRepository    $repository,
        AccountEntityManager $entityManager,
        AccountValidation    $validator
    )
    {
        $this->view = $view;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function processDeposit(): void
    {
        $input = $_POST["amount"] ?? null;

        if ($input !== null) {
            $validAmount = $this->validator->validateAllCriteria($input);

            if (is_float($validAmount)) {
                $date = date('Y-m-d');
                $time = date('H:i:s');
                $saveData = new AccountDTO();
                $saveData->amount = $validAmount;
                $saveData->date = $date;
                $saveData->time = $time;
                $this->entityManager->saveDeposit($saveData);
                $this->success = "Die Transaktion wurde erfolgreich gespeichert!";
            } else {
                $this->error = $validAmount;
            }
        }

        $balance = $this->repository->calculateBalance();

        if (isset($_POST["logout"])) {
            $_SESSION["loginStatus"] = false;
            session_unset();
            header("Refresh:0");
        }

        $loginStatus = false;
        if (isset($_SESSION["loginStatus"])) {
            $loginStatus = $_SESSION["loginStatus"];
        }

        $activeUser = null;
        if (isset($_SESSION["username"])) {
            $activeUser = $_SESSION["username"];
        }

        $this->view->addParameter('balance', $balance);
        $this->view->addParameter('loginStatus', $loginStatus);
        $this->view->addParameter('activeUser', $activeUser);
        $this->view->addParameter('error', $this->error);
        $this->view->addParameter('success', $this->success);

        $this->view->display('deposit.twig');
    }
}
