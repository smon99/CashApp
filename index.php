<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$balance = 0;
$dailyDeposit = 0;
$hourDeposit = 0;
$date = 0;
$error = null;

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
            echo 'Ich habe die DATEI geschpeichert';
            if ($error === null) {
                $success = "Die Transaktion wurde erfolgreich gespeichert!";
            }
        }
    } else {
        $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
    }

} elseif (isset($correctInput) && $correctInput > 50 && is_numeric($correctInput)) {     // limit exceeded for one deposit
    $error = "Einzahlungslimit von 50€ pro Einzahlung überschritten!";
} elseif (isset($correctInput) && is_numeric($correctInput) === false) {
    $error = "Eingabe ist keine Zahl!";
}

$balance = array_sum(array_column($transaction, "amount"));

include 'form.php';