<!DOCTYPE html>
<html lang="en">
<head><title>Registrierung</title></head>

<h1>
    Registrierung
</h1>

<form action='user.php' method='POST'>
    Name: <input type='text' name='username' value="<?php if(isset($tempUserName)){echo $tempUserName;} ?>" > <br>
    Email: <input type='text' name='mail' value="<?php if(isset($tempMail)){echo $tempMail;} ?>" > <br>
    Passwort: <input type='text' name='password' value="<?php if(isset($tempPassword)){echo $tempPassword;} ?>" > <br>
    <input type='submit' value='Hochladen'>
</form>

</html>