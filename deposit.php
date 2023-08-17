<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/View');
$twig = new Environment($loader);

session_start();

if (!isset($_SESSION["loginStatus"])) {     //define locals here so no session var in view
    $_SESSION["loginStatus"] = false;
    $loginStatus = $_SESSION["loginStatus"];
} else {
    $loginStatus = $_SESSION["loginStatus"];
    $activeUser = $_SESSION["username"];
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$balance = 0;
$dailyDeposit = 0;
$hourDeposit = 0;
$date = 0;
$error = null;
$success = null;

if (!file_exists("account.json")) {
    file_put_contents("account.json", json_encode([]));
}

$transaction = json_decode(file_get_contents("account.json"), true);

if (isset($_POST["amount"])) {
    $correctInput = str_replace(['.', ','], ['', '.'], $_POST["amount"]);     //Convert all formats to normal php float
}

if (isset($correctInput) && is_numeric($correctInput) && $correctInput <= 50 && $correctInput >= 0.01) {     //Validate if input is a number //0.01 <= input < 50

    $date = date('Y-d-m');
    $time = date('H:i:s');
    $timestampCurrent = strtotime($time);
    $hourDeposit = $correctInput;
    $dailyDeposit = $correctInput;
    $newTransaction = [
        "amount" => $correctInput,
        "date" => $date,
        "time" => $time,
    ];

    if (!empty($transaction)) {
        foreach ($transaction as $deposit) {
            if ($deposit["date"] === $date) {
                $dailyDeposit += $deposit["amount"];
                $timestampHistory = strtotime($deposit["time"]);
                if ($timestampHistory >= $timestampCurrent - (60 * 60)) {
                    $hourDeposit += $deposit["amount"];
                }
            }
        }
    }

    if ($dailyDeposit <= 500 && $hourDeposit <= 100) {     //daily limit of 500 and hour limit of 100 not exceeded
        $transaction[] = $newTransaction;
        $dataToSave = $transaction;
    } elseif ($dailyDeposit > 500) {
        $error = "Tägliches Einzahlungslimit von 500€ überschritten!";
    } else {
        $error = "Stündliches Einzahlungslimit von 100€ überschritten!";
    }

    if ($error === null) {
        if (file_put_contents("account.json", json_encode($transaction, JSON_PRETTY_PRINT), LOCK_EX)) {     //Transaction successful
            $success = "Die Transaktion wurde erfolgreich gespeichert!";
        }
    }

} elseif (isset($correctInput) && $correctInput > 50 && is_numeric($correctInput)) {     // limit exceeded for one deposit
    $error = "Einzahlungslimit von 50€ pro Einzahlung überschritten!";
} elseif (isset($correctInput) && is_numeric($correctInput) === false) {
    $error = "Eingabe ist keine Zahl!";
}

$balance = array_sum(array_column($transaction, "amount"));

if (isset($_POST["logout"])) {
    $_SESSION["loginStatus"] = false;
    session_unset();
    header("Refresh:0");
}

echo $twig->render('depositView.twig', ['balance' => $balance, 'loginStatus' => $loginStatus, 'activeUser' => $activeUser, 'error' => $error, 'success' => $success]);