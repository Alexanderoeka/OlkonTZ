<?php

session_start();

if (!isset($_SESSION['changePasswordHash'])) {
    header('Location: http://w91715mr.beget.tech/');
    exit();
}


if (isset($_SESSION['errorPass'])) {
    echo $_SESSION['errorPass'] . '<br>';
    unset($_SESSION['errorPass']);
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Change password</title>
</head>

<body>
    <form method="POST" action="/?class=Changepassword&method=passwordAcceptance">
        <input type="password" name="pass1">
        <input type="password" name="pass2">
        <input type="submit" value="New password">
    </form>
</body>

</html>