<?php declare(strict_types=1);

session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mail = $_POST["mail"];
    $password = $_POST["password"];

    $user = json_decode(file_get_contents("user.json"), true);

    if (!empty($user)) {
        foreach ($user as $userCheck) {
            if ($userCheck["eMail"] === $mail) {
                $passwordCheck = $userCheck["password"];

                if ($passwordVerify = password_verify($password, $passwordCheck)) {
                    $_SESSION["username"] = $userCheck["user"];
                    $_SESSION["loginStatus"] = true;
                    echo "logged in as ", $userCheck["user"];

                } else {
                    $_SESSION["loginStatus"] = false;
                    echo "nice try";
                }
            }
        }
    }
    if ($_SESSION["loginStatus"] === true) {
        header("Location: http://0.0.0.0:8000/deposit.php");
        exit();
    }
}

include __DIR__ . '/View/loginView.twig';