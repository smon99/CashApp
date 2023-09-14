<?php

namespace App\Core;

use App\Model\AccountRepository;

class AccountValidation
{
    public function singleDepositLimit(float $amount): bool
    {
        if ($amount > 50) {
            return false;
        }
        return true;
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

        if ($amount >= 100.00) {
            return false;
        }
        return true;
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

        if ($amount >= 500.00) {
            return false;
        }
        return true;
    }
}