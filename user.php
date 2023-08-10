<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$error = null;
$passwordCheck = null;

if (!file_exists("user.json")) {
    file_put_contents("user.json", json_encode([]));
}

$user = json_decode(file_get_contents("user.json"), true);

function validatePassword($passwordCheck): bool     //validate if password criteria is met
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

    if (validatePassword($passwordCheck) === true) {

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
    file_put_contents("user.json", json_encode($user, JSON_PRETTY_PRINT));
    echo 'Yeah';
} else {
    echo $error;
}

include 'registration.php';