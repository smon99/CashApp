<!DOCTYPE html>
<html lang="en">
<head><title>Geld hochladen</title></head>

<?php
if (isset($success)) {
    echo $success;
}
if (isset($error)) {
    echo $error;
}
echo "<br>";
echo "Kontostand: $balance €";
?>

<h1>
    Geld hochladen
</h1>

<form action='index.php' method='POST'>
    Betrag: <input type='number' name='amount' step='0.01'>
    <input type='submit' value='Hochladen'>
</form>

</html>