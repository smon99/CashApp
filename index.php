<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$balance = 0;
$dailyDeposit = 0;
$hourDeposit = 0;
$date = 0;

if (!file_exists("account.json")) {
    file_put_contents("account.json", json_encode([]));
}

if (isset($_POST["amount"])) {
    $correctInput = str_replace(['.', ','], ['', '.'], $_POST["amount"]);     //Convert all formats to normal php float
}

if (isset($correctInput) && is_numeric($correctInput) && $correctInput < 50 && $correctInput >= 0.01) {     //Also validate if input is a number //0.01 <= input < 50
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

    if (file_exists("account.json")) {
        $deposits = json_decode(file_get_contents("account.json"), true);     //Sum up deposit per day & hour to check limit
        foreach ($deposits as $deposit) {
            if ($deposit["date"] === $date) {
                $dailyDeposit += $deposit["amount"];
                $timestampHistory = strtotime($deposit["time"]);
                if ($timestampHistory >= $timestampCurrent - (60 * 60)) {
                    $hourDeposit += $deposit["amount"];
                }
            }
        }
    }

    if ($dailyDeposit <= 500 && $hourDeposit <= 100) {                                                                                            //daily limit of 500 and hour limit of 100 not exceeded
        $transactionData = file_exists("account.json") ? json_decode(file_get_contents("account.json"), true) : [];
        $transactionData[] = $newTransaction;

        if (!file_put_contents("account.json", json_encode($transactionData, JSON_PRETTY_PRINT), LOCK_EX)) {     //Transaction failed?
            $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
        } else {
            $success = "Die Transaktion wurde erfolgreich gespeichert!";
        }
    } elseif ($dailyDeposit > 500 || $hourDeposit > 100) {
        $error = ($dailyDeposit > 500) ? "Tägliches Einzahlungslimit von 500€ überschritten!" : "Stündliches Einzahlungslimit von 100€ überschritten!";
    }

} elseif (isset($correctInput) && $correctInput > 50 && is_numeric($correctInput)) {     // limit exceeded for one deposit
    echo "Einzahlungslimit von 50€ pro Einzahlung überschritten!";
    echo "<br>";
    $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
} elseif (isset($correctInput) && is_numeric($correctInput) === false) {
    echo "Eingabe ist keine Zahl!";
}

$transactions = file_exists("account.json") ? json_decode(file_get_contents("account.json"), true) : [];
foreach ($transactions as $transaction) {
    $balance += $transaction["amount"];
}

include 'form.php';