<?php declare(strict_types=1);

namespace App\Core;

use App\Controller\AccountController;
use App\Controller\ErrorController;
use App\Controller\LoginController;
use App\Controller\TransactionController;
use App\Controller\UserController;

class ControllerProvider
{
    public function getList(): array
    {
        return [

            "account" => AccountController::class,

            "login" => LoginController::class,

            "user" => UserController::class,

            "transaction" => TransactionController::class,

            "unknown" => ErrorController::class,

        ];
    }
}