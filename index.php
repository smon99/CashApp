<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$balance = 0;
$dailyDeposit = 0;
$hourDeposit = 0;
$date = 0;

if (isset($_POST["amount"])) {
    $correctInput = str_replace(array('.', ','), array('', '.'), $_POST["amount"]);     //Convert all formats to normal php float
}

if (isset($correctInput) && is_numeric($correctInput) && $correctInput < 50 && $correctInput >= 0.01) {     //Form submitted via POST Method? //Also validate if input is a number //0.01 <= input < 50
    $date = date('Y-d-m');
    $time = date('H:i:s');
    $timestampCurrent = strtotime($time);
    $hourDeposit = $correctInput;
    $dailyDeposit = $correctInput;
    $newTransaction = array(
        "amount" => $correctInput,
        "date" => $date,
        "time" => $time
    );

    if (file_exists("account.json")) {
        $deposits = json_decode(file_get_contents("account.json"), true);     //Sum up deposit per day $ hour to check limit
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

    echo "Daily: ", $dailyDeposit, " Per hour: ", $hourDeposit;     //Check used balance (only temp)
    echo "<br>";

    if (file_exists("account.json") === false) {     //First entry in transaction log?
        $dataToSave = array($newTransaction);
        $dailyDeposit = $correctInput;
    } elseif ($dailyDeposit <= 500 && $hourDeposit <= 100) {                                            //daily limit of 500 and hour limit of 100 not exceeded
        $oldTransactions = json_decode(file_get_contents("account.json"), false);
        $oldTransactions[] = $newTransaction;
        $dataToSave = $oldTransactions;
    } elseif ($dailyDeposit > 500) {
        $dataToSave = json_decode(file_get_contents("account.json"), false);
    } elseif ($hourDeposit > 100) {
        $dataToSave = json_decode(file_get_contents("account.json"), false);
    }

    if (!file_put_contents("account.json", json_encode($dataToSave, JSON_PRETTY_PRINT), LOCK_EX)) {     //Transaction successful or failed?
        $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
    } elseif ($dailyDeposit > 500) {
        $error = "Tägliches Einzahlungslimit von 500€ überschritten!";
    } elseif ($hourDeposit > 100) {
        $error = "Stündliches Einzahlungslimit von 100€ überschritten!";
    } else {
        $success = "Die Transaktion wurde erfolgreich gespeichert!";
    }

} elseif (isset($correctInput) && $correctInput > 50 && is_numeric($correctInput)) {     // limit exceeded for one deposit
    echo "Einzahlungslimit von 50€ pro Einzahlung überschritten!";
    echo "<br>";
    $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
} elseif (isset($correctInput) && is_numeric($correctInput) === false) {
    echo "Eingabe ist keine Zahl!";
}

if (file_exists("account.json")) {
    $transactions = json_decode(file_get_contents("account.json"), true);     //Calculate balance
    foreach ($transactions as $transaction) {
        $balance += $transaction["amount"];
    }
}

include 'form.php';