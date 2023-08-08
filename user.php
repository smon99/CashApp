<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (isset($_POST["username"], $_POST["mail"], $_POST["password"])) {

    $user = $_POST["username"];
    $eMail = $_POST["mail"];
    $password = $_POST["password"];

    $newUser = array(
        "user" => $user,
        "eMail" => $eMail,
        "password" => $password,
    );

    if (file_exists("account.json") === false) {     //First entry in transaction log?
        $UserToSave = array($newUser);
    } else {
        $oldUsers = json_decode(file_get_contents("user.json"), false);
        $UserToSave = $oldUsers;
    }

    if (!file_put_contents("user.json", json_encode($UserToSave, JSON_PRETTY_PRINT), LOCK_EX)) {
        $error = "Fehler!";
    }else{
        $success = "Erfolg!";
    }
}

include 'registration.php';