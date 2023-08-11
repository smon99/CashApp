<!DOCTYPE html>
<html lang="en">
<head><title>Login</title></head>

<h1>
    Anmelden
</h1>

<form action='login.php' method='POST'>
    E-Mail: <input type='text' name='mail'> <br>
    Passwort: <input type='text' name='password'> <br>
    <input type='submit' value='Einloggen'>
</form>

<?php

if (isset($loginStatus)){
    header("Location: http://0.0.0.0:8000/index.php");
    exit();
}

?>

</html>