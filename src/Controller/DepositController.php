<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\AccountRepository;
use App\Model\AccountEntityManager;
use App\Core\ViewInterface;

class DepositController
{
    private $view;
    private $repository;
    private $entityManager;
    private $error;
    private $success;

    public function __construct(ViewInterface $view)
    {
        $this->view = $view;

        $repository = new AccountRepository();
        $entityManager = new AccountEntityManager();
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function processDeposit()
    {
        $correctInput = $this->getCorrectInput();

        if ($correctInput !== null) {
            $this->validateDeposit($correctInput);

            if ($this->error === null) {

                $path = '/../Model/account.json';
                $this->entityManager->saveDeposit([
                    "amount" => $correctInput,
                    "date" => date('Y-d-m'),
                    "time" => date('H:i:s'),
                ], $path);
                $this->success = "Die Transaktion wurde erfolgreich gespeichert!";
            }
        }

        $balanceData = $this->repository->calculateTimeBalance($correctInput);
        $balance = $balanceData["balance"];

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

        $error = null;
        if (isset($this->error)) {
            $error = $this->error;
        }

        $success = null;
        if (isset($this->success)) {
            $success = $this->success;
        }

        $this->view->addParameter('balance', $balance);
        $this->view->addParameter('loginStatus', $loginStatus);
        $this->view->addParameter('activeUser', $activeUser);
        $this->view->addParameter('error', $error);
        $this->view->addParameter('success', $success);

        $this->view->display('deposit.twig');
    }

    private function getCorrectInput(): array|string|null
    {
        if (isset($_POST["amount"])) {
            $input = str_replace(['.', ','], ['', '.'], $_POST["amount"]);

            if (empty($input)) {
                $this->error = "Bitte einen Betrag eingeben!";
                return null;
            }

            return $input;
        }

        return null;
    }

    private function validateDeposit($correctInput)
    {
        if ($correctInput === null || $correctInput === '') {
            $this->error = "Bitte einen Betrag eingeben!";
            return;
        }

        $correctInput = (float)$correctInput;

        if (is_numeric($correctInput) && $correctInput >= 0.01 && $correctInput <= 50) {
            $balanceData = $this->repository->calculateTimeBalance($correctInput);
            $dailyDeposit = $balanceData["day"];
            $hourDeposit = $balanceData["hour"];

            if ($dailyDeposit <= 500 && $hourDeposit <= 100) {
                $this->success = "Die Transaktion wurde erfolgreich gespeichert!";
            } elseif ($dailyDeposit > 500) {
                $this->error = "Tägliches Einzahlungslimit von 500€ überschritten!";
            } else {
                $this->error = "Stündliches Einzahlungslimit von 100€ überschritten!";
            }
        } else {
            $this->error = "Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!";
        }
    }

}