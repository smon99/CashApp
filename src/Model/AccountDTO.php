<?php declare(strict_types=1);

namespace App\Model;

class AccountDTO
{
    public int $transactionID = 0;
    public int $userID = 0;
    public float $value = 0.00;
    public string $transactionDate = '';
    public string $transactionTime = '';
}
