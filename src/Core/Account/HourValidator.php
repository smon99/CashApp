<?php declare(strict_types=1);

namespace App\Core\Account;

class HourValidator implements AccountValidationInterface
{
    public function validate(float $amount): string|bool
    {
        $accountData = json_decode(file_get_contents(__DIR__ . '/../../Model/account.json'), true);

        $date = date('Y-m-d');
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
        if ($amount < 100.00) {
            return true;
        }
        return 'Stündliches Einzahlungslimit von 100€ überschritten!';
    }
}