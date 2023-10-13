<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Container;
use App\Core\Redirect;
use App\Core\View;
use App\Model\AccountRepository;

class HistoryController implements ControllerInterface
{
    private View $view;
    private Redirect $redirect;
    private AccountRepository $accountRepository;

    public function __construct(Container $container)
    {
        $this->view = $container->get(View::class);
        $this->redirect = $container->get(Redirect::class);
        $this->accountRepository = $container->get(AccountRepository::class);
    }

    public function action(): void
    {
        if (!isset($_SESSION["loginStatus"])) {
            $this->redirect->redirectTo('http://0.0.0.0:8000/?page=login');
        }

        $transactions = $this->accountRepository->transactionPerUserID($_SESSION["userID"]);

        $this->view->addParameter('transactions', $transactions);
        $this->view->setTemplate('history.twig');
    }
}