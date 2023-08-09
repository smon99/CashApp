<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$error = null;

if (!file_exists("user.json")) {
    file_put_contents("user.json", json_encode([]));
}

$user = json_decode(file_get_contents("user.json"), true);

function validatePassword($password): bool
{
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChar = preg_match('@[^\w]@', $password);

    $minLength = 6;

    return $uppercase && $lowercase && $number && $specialChar && strlen($password) >= $minLength;
}

if (isset($_POST["username"], $_POST["mail"], $_POST["password"])) {
    $userName = $_POST["username"];
    $eMailCheck = $_POST["mail"];
    $password = $_POST["password"];

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

    if (validatePassword($password) === true) {
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

if (!isset($error) && file_put_contents("user.json", json_encode($user, JSON_PRETTY_PRINT), LOCK_EX)) {
    echo "yes";
}else{
    echo $error;
}

include 'registration.php';