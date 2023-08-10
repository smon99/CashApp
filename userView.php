<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registrierung</title>
</head>
<body>
<h1>Registrierung</h1>

<form action='user.php' method='POST'>
    Name: <input type='text' name='username' <?php if(isset($tempUserName)){ echo "value='$tempUserName'"; }?>><br>
    Email: <input type='text' name='mail' <?php if(isset($tempMail)){ echo "value='$tempMail'"; }?>><br>
    Passwort: <input type='text' name='password' <?php if(isset($tempPassword)){ echo "value='$tempPassword'"; }?>><br>
    <input type='submit' value='Registrieren'>
</form>
</body>
</html>