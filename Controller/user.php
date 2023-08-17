<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../View');
$twig = new Environment($loader);

$error = null;
$passwordCheck = null;
$tempUserName = null;
$tempMail = null;
$tempPassword = null;

if (!file_exists(__DIR__ . '/../Model/user.json')) {
    file_put_contents(__DIR__ . '/../Model/user.json', json_encode([]));
}

$user = json_decode(file_get_contents(__DIR__ . '/../Model/user.json'), true);

function validatePassword($passwordCheck): bool
{
    $uppercase = preg_match('@[A-Z]@', $passwordCheck);
    $lowercase = preg_match('@[a-z]@', $passwordCheck);
    $number = preg_match('@[0-9]@', $passwordCheck);
    $specialChar = preg_match('@[^\w]@', $passwordCheck);
    $minLength = 6;

    return $uppercase && $lowercase && $number && $specialChar && strlen($passwordCheck) >= $minLength;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST["username"]) || empty($_POST["mail"]) || empty($_POST["password"])) {
        $error = "Alle Felder müssen ausgefüllt sein!";

        if (isset($_POST["username"])) {
            $tempUserName = $_POST["username"];
        }
        if (isset($_POST["mail"])) {
            $tempMail = $_POST["mail"];
        }
        if (isset($_POST["password"])) {
            $tempPassword = $_POST["password"];
        }

    } elseif (isset($_POST["username"], $_POST["mail"], $_POST["password"])) {

        $userName = $_POST["username"];
        $eMailCheck = $_POST["mail"];
        $passwordCheck = $_POST["password"];

        if (!empty($user)) {
            foreach ($user as $userData) {
                if ($userData["eMail"] === $eMailCheck) {
                    $error = "Fehler eMail bereits vergeben";
                    break;
                }
            }
        }

        if (filter_var($eMailCheck, FILTER_VALIDATE_EMAIL)) {
            $eMail = $eMailCheck;
        }
        if (!isset($eMail)) {
            $error = "Bitte gültige eMail eingeben!";
        }

        if (validatePassword($passwordCheck)) {

            $password = password_hash($passwordCheck, PASSWORD_DEFAULT);

            if (!isset($error)) {
                $newUser = [
                    "user" => $userName,
                    "eMail" => $eMail,
                    "password" => $password,
                ];

                $user[] = $newUser;
            }
        } else {
            $error = "Passwort Anforderungen nicht erfüllt (find out yourself)";
        }
    }

    if (!isset($error)) {
        file_put_contents(__DIR__ . '/../Model/user.json', json_encode($user, JSON_PRETTY_PRINT));
        header("Location: http://0.0.0.0:8000/Controller/login.php");
        exit();
    } else {
        echo $error;
    }
}
echo $twig->render('user.twig', ['tempUserName' => $tempUserName, 'tempMail' => $tempMail, 'tempPassword' => $tempPassword]);