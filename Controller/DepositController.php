<?php declare(strict_types=1);

namespace Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DepositController
{
    private $twig;
    private $transaction;
    private $error;
    private $success;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader);

        $this->transaction = json_decode(file_get_contents(__DIR__ . '/../Model/account.json'), true);
        if (!$this->transaction) {
            file_put_contents(__DIR__ . '/../Model/account.json', json_encode([]));
            $this->transaction = [];
        }
    }

    public function processDeposit()
    {
        $correctInput = $this->getCorrectInput();

        if ($correctInput !== null) {
            $this->validateDeposit($correctInput);

            if ($this->error === null) {
                $this->saveTransaction();
            }
        }

        $balance = $this->calculateBalance();

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
            return str_replace(['.', ','], ['', '.'], $_POST["amount"]);
        }
        return null;
    }

    private function validateDeposit($correctInput)
    {
        if (is_numeric($correctInput) && $correctInput >= 0.01 && $correctInput <= 50) {
            $date = date('Y-d-m');
            $time = date('H:i:s');
            $timestampCurrent = strtotime($time);
            $hourDeposit = $correctInput;
            $dailyDeposit = $correctInput;

            if (!empty($this->transaction)) {
                foreach ($this->transaction as $deposit) {
                    if ($deposit["date"] === $date) {
                        $dailyDeposit += $deposit["amount"];
                        $timestampHistory = strtotime($deposit["time"]);
                        if ($timestampHistory >= $timestampCurrent - (60 * 60)) {
                            $hourDeposit += $deposit["amount"];
                        }
                    }
                }
            }

            if ($dailyDeposit <= 500 && $hourDeposit <= 100) {
                $this->transaction[] = [
                    "amount" => $correctInput,
                    "date" => $date,
                    "time" => $time,
                ];
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

    private function saveTransaction()
    {
        file_put_contents(__DIR__ . '/../Model/account.json', json_encode($this->transaction, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    private function calculateBalance()
    {
        return array_sum(array_column($this->transaction, "amount"));
    }
}