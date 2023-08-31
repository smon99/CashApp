<?php declare(strict_types=1);

namespace Model;

class AccountRepository
{
    public function calculateTimeBalance($correctInput): ?array
    {
        $accountData = json_decode(file_get_contents(__DIR__ . '/../Model/account.json'), true);

        $date = date('Y-d-m');
        $time = date('H:i:s');
        $timestampCurrent = strtotime($time);

        $hourDeposit = $correctInput;
        $dailyDeposit = $correctInput;

        $balance = array_sum(array_column($accountData, "amount"));

        foreach ($accountData as $transactionSet) {
            if ($transactionSet["date"] === $date) {
                $dailyDeposit += $transactionSet["amount"];
                $timestampHistory = strtotime($transactionSet["time"]);
                if ($timestampHistory >= $timestampCurrent - (60 * 60)) {
                    $hourDeposit += $transactionSet["amount"];
                }
            }
        }
        $balanceData = [
            "balance" => $balance,
            "day" => $dailyDeposit,
            "hour" => $hourDeposit,
        ];
        return $balanceData;
    }
}