<!DOCTYPE html>
<html lang="en">
<head><title>CashApp</title></head>

<?php

if ($loginStatus === true) {
    echo "angemeldet als: ", $activeUser;
    echo " <form method='post' action='../deposit.php'>
           <button type='submit' name='logout'>abmelden</button>
           </form>";
} else {
    echo "<a href=http://0.0.0.0:8000/login.php><button>anmelden</button></a>";
    echo "<a href=http://0.0.0.0:8000/user.php><button>registrieren</button></a> <br>";
}

if (isset($success)) {
    echo $success;
}
if (isset($error)) {
    echo "<br>", $error;
    echo "Fehler! Die Transaktion wurde nicht gespeichert!", "<br>";
}
echo "<br>";
echo "Kontostand: $balance €";
?>

<h1>
    Geld hochladen
</h1>

<form action='../deposit.php' method='POST'>
    Betrag: <input type='text' name='amount'>
    <input type='submit' value='Hochladen'>
</form>

</html>