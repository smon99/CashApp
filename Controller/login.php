<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../View');
$twig = new Environment($loader);

session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mail = $_POST["mail"];
    $password = $_POST["password"];

    $user = json_decode(file_get_contents(__DIR__ . '/../Model/user.json'), true);

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
        header("Location: http://0.0.0.0:8000/Controller/deposit.php");
        exit();
    }
}

echo $twig->render('login.twig');