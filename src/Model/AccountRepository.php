<?php declare(strict_types=1);

namespace App\Model;

class AccountRepository
{
    public const ACCOUNT_DEFAULT_PATH = __DIR__ . '/account.json';

    private string $path;
    private AccountMapper $accountMapper;

    public function __construct(?string $path = null, AccountMapper $accountMapper)
    {
        if ($path === null) {
            $this->path = self::ACCOUNT_DEFAULT_PATH;
        } else {
            $this->path = $path;
        }
        $this->accountMapper = $accountMapper;
    }

    public function calculateTimeBalance($correctInput): ?array
    {
        // Use the AccountMapper to get an array of AccountDTO objects
        $accountDTOList = $this->accountMapper->JsonToDTO($this->path);

        $date = date('Y-d-m');
        $time = date('H:i:s');
        $timestampCurrent = strtotime($time);

        $hourDeposit = $correctInput;
        $dailyDeposit = $correctInput;

        $balance = 0.00;

        foreach ($accountDTOList as $accountDTO) {
            $balance += $accountDTO->amount;

            if ($accountDTO->date === $date) {
                $dailyDeposit += $accountDTO->amount;
                $timestampHistory = strtotime($accountDTO->time);
                if ($timestampHistory >= $timestampCurrent - (60 * 60)) {
                    $hourDeposit += $accountDTO->amount;
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
