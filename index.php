<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$balance = 0;

if (isset($_POST["amount"]) && is_numeric($_POST["amount"]) && $_POST["amount"] < 50 && $_POST["amount"] >= 0.01) {     //Form submitted via POST Method? //Also validate if input is a number //0.01 <= input < 50
    if ($_POST["amount"] > 0) {
        $date = date('Y-d-m');
        $time = date('H:i:s');
        $newTransaction = array(
            "amount" => $_POST["amount"],
            "date" => $date,
            "time" => $time
        );
    }

    if (file_exists("account.json") === false) {     //First entry in transaction log?
        $dataToSave = array($newTransaction);
    } else {
        $oldTransactions = json_decode(file_get_contents("account.json"), false);
        $oldTransactions[] = $newTransaction;
        $dataToSave = $oldTransactions;
    }

    if (!file_put_contents("account.json", json_encode($dataToSave, JSON_PRETTY_PRINT), LOCK_EX)) {     //Transaction successful or failed?
        $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
    } else {
        $success = "Die Transaktion wurde erfolgreich gespeichert!";
    }
} elseif (isset($_POST["amount"]) && $_POST["amount"] > 50) {     // limit exceeded for one deposit
    echo "Einzahlungslimit von 50€ am pro Einzahlung überschritten!";
    echo "<br>";
    $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
}

if (file_exists("account.json")) {
    $transactions = json_decode(file_get_contents("account.json"), true);     //Calculate balance
    foreach ($transactions as $transaction) {
        $balance += $transaction["amount"];
    }
}

include 'form.php';