<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\View;
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
    private $success;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->repository = $container->get(AccountRepository::class);
        $this->entityManager = $container->get(AccountEntityManager::class);
        $this->validator = $container->get(AccountValidation::class);
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

        $this->view->setTemplate('deposit.twig');
        $this->view->display();
    }

    public function getCorrectAmount(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }
}
