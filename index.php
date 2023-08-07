<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$balance = 0;

if (isset($_POST["amount"])) {     //Form submitted via POST Method?
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
        $firstTransaction = array($newTransaction);
        $dataToSave = $firstTransaction;
    } else {
        $oldTransactions = json_decode(file_get_contents("account.json"));
        $oldTransactions[] = $newTransaction;
        $dataToSave = $oldTransactions;
    }

    if (!file_put_contents("account.json", json_encode($dataToSave, JSON_PRETTY_PRINT), LOCK_EX)) {     //Transaction successfull or failed?
        $error = "Fehler! Die Transaktion wurde nicht gespeichert!";
    } else {
        $success = "Die Transaktion wurde erfolgreich gespeichert!";
    }
}

if (file_exists("account.json")) {
    $transactions = json_decode(file_get_contents("account.json"), true);     //Calculate balance
    foreach ($transactions as $transaction) {
        $balance += $transaction["amount"];
    }
}

include 'form.php';