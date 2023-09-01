<?php declare(strict_types=1);

namespace Controller;

use Model\AccountRepository;
use Model\AccountEntityManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DepositController
{
    private $twig;
    private $repository;
    private $entityManager;
    private $error;
    private $success;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader);

        $repository = new \Model\AccountRepository();
        $entityManager = new \Model\AccountEntityManager();
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function processDeposit()
    {
        $correctInput = $this->getCorrectInput();

        if ($correctInput !== null) {
            $this->validateDeposit($correctInput);

            if ($this->error === null) {
                $this->entityManager->saveDeposit([
                    "amount" => $correctInput,
                    "date" => date('Y-d-m'),
                    "time" => date('H:i:s'),
                ]);
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

        echo $this->twig->render('deposit.twig', [
            'balance' => $balance,
            'loginStatus' => $loginStatus,
            'activeUser' => $activeUser,
            'error' => $error,
            'success' => $success,
        ]);
    }

    private function getCorrectInput()
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