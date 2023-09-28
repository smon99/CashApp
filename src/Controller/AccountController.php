<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\AccountDTO;
use App\Model\AccountRepository;
use App\Core\ViewInterface;
use App\Core\AccountValidation;
use App\Core\Account\AccountValidationException;

use App\Model\AccountEntityManager;

class AccountController implements ControllerInterface
{
    private AccountValidation $validator;
    private ViewInterface $view;
    private AccountRepository $repository;
    private AccountEntityManager $entityManager;
    private ?string $success = null;

    public function __construct(
        ViewInterface        $view,
        AccountRepository    $repository,
        AccountEntityManager $entityManager,
        AccountValidation    $validator
    )
    {
        $this->view = $view;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function action(): void
    {
        $input = $_POST["amount"] ?? null;

        if ($input !== null) {
            try {
                $validateThis = $this->getCorrectAmount($input);

                $this->validator->collectErrors($validateThis);

                $amount = $validateThis;

                $date = date('Y-m-d');
                $time = date('H:i:s');
                $saveData = new AccountDTO();
                $saveData->amount = $amount;
                $saveData->date = $date;
                $saveData->time = $time;
                $this->entityManager->saveDeposit($saveData);
                $this->success = "Die Transaktion wurde erfolgreich gespeichert!";
            } catch (AccountValidationException $e) {
                $this->view->addParameter('error', $e->getMessage());
            }
        }

        $balance = $this->repository->calculateBalance();

        if (isset($_POST["logout"])) {
            session_destroy();
            header("Refresh:0");
        }

        $activeUser = null;
        $loginStatus = false;
        if (isset($_SESSION["loginStatus"])) {
            $loginStatus = $_SESSION["loginStatus"];
            $activeUser = $_SESSION["username"];
        }

        $this->view->addParameter('balance', $balance);
        $this->view->addParameter('loginStatus', $loginStatus);
        $this->view->addParameter('activeUser', $activeUser);
        $this->view->addParameter('success', $this->success);

        $this->view->display('deposit.twig');
    }

    public function getCorrectAmount(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }
}
