<?php declare(strict_types=1);

namespace App\Core;

class AccountValidation
{
    public function getCorrectAmount(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }

    public function existsIsNumeric($amount): bool
    {
        if (!is_numeric($amount)) {
            return false;
        }
        return true;
    }

    public function singleDepositLimit(float $amount): bool
    {
        return $amount >= 0.01 && $amount <= 50;
    }

    public function hourDepositLimit(float $amount): bool
    {
        $accountData = json_decode(file_get_contents(__DIR__ . '/../Model/account.json'), true);

        $date = date('Y-d-m');
        $time = date('H:i:s');
        $timestampCurrent = strtotime($time);

        foreach ($accountData as $transactionSet) {
            if ($date === $transactionSet["date"]) {
                $timestampHistory = strtotime($transactionSet["time"]);
            }
            if (isset($timestampHistory) && $timestampHistory >= $timestampCurrent - (60 * 60)) {
                $amount += $transactionSet["amount"];
            }
        }

        return $amount < 100.00;
    }

    public function dayDepositLimit(float $amount): bool
    {
        $accountData = json_decode(file_get_contents(__DIR__ . '/../Model/account.json'), true);

        $date = date('Y-d-m');

        foreach ($accountData as $transactionSet) {
            if ($date === $transactionSet["date"]) {
                $amount += $transactionSet["amount"];
            }
        }

        if ($amount >= 500.0) {
            return false;
        }
        return true;
    }

    public function validateAllCriteria(string $input): float|string
    {
        $amount = $this->getCorrectAmount($input);

        $numeric = $this->existsIsNumeric($amount);
        $single = $this->singleDepositLimit($amount);
        $hour = $this->hourDepositLimit($amount);
        $day = $this->dayDepositLimit($amount);

        $error = 'Ein Fehler ist aufgetreten Einzahlung wurde nicht durchgeführt';

        if ($numeric === false) {
            $error = 'Bitte einen Betrag eingeben!';
        }
        if ($single === false) {
            $error = 'Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!';
        }
        if ($hour === false) {
            $error = 'Stündliches Einzahlungslimit von 100€ überschritten!';
        }
        if ($day === false) {
            $error = 'Tägliches Einzahlungslimit von 500€ überschritten!';
        }

        if ($numeric && $single && $hour && $day === true) {
            return $amount;
        }
        return $error;
    }
}