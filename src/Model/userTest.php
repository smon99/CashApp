<a href="http://0.0.0.0:8000/src/?input=index">
    <button>index</button>
</a> <br><br>

<form action='http://0.0.0.0:8000/src/Model/userTest.php' method='POST'>
    user: <label>
        <input type='text' name='name'>
    </label>
    <input type='submit' value='check'>
</form>

<form action='http://0.0.0.0:8000/src/Model/userTest.php' method='POST'>
    mail: <label>
        <input type='text' name='mail'>
    </label>
    <input type='submit' value='check'>
</form>

<form action='http://0.0.0.0:8000/src/Model/userTest.php' method='POST'>
    amount: <label>
        <input type='text' name='amount'>
    </label>
    <input type='submit' value='check'>
</form>

<?php

require __DIR__ . '/UserRepository.php';
require __DIR__ . '/AccountRepository.php';

use Model\UserRepository;
use Model\AccountRepository;

$userCheck = $_POST['name'];
$mailCheck = $_POST['mail'];

$correctInput = $_POST_['amount'];

$userRepository = new UserRepository();
$accountRepository = new AccountRepository();

if (isset($_POST['name'])) {
    if ($userRepository->findByUsername($userCheck) === null) {
        if (isset($_POST['name'])) {
            echo "no name match";
        }
    } else {
        print_r($userRepository->findByUsername($userCheck));
    }
}

if (isset($_POST['mail'])) {
    if ($userRepository->findByMail($mailCheck) === null) {
        if (isset($_POST['mail'])) {
            echo "no mail match";
        }
    } else {
        print_r($userRepository->findByMail($mailCheck));
    }
}

if (isset($_POST['amount'])){
    $output = $accountRepository->calculateTimeBalance($correctInput);
    print_r($output);
}
