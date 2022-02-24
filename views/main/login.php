<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>

<body>
    <?php


    echo isset($_SESSION['message']['login']) ? $_SESSION['message']['login'] : '' . '<br>';
    echo isset($_SESSION['message']['password']) ? $_SESSION['message']['password'] : '';
    unset($_SESSION['message']);
    ?>

    <form method="POST" action="/?class=main&method=checklogin">
        <input type="text" name="login" value="" placeholder="Enter your login!">
        <input type="password" name="password" value="" placeholder="Enter your password!">
        <input type="submit" value="Login">

    </form>
    <br>
    <a href="/?class=changepassword&method=recoverpassword">Recover password</a>
    <br>
    <br>
    <a href="/?class=insert&method=queryinsertdb">Ввести данные</a>

</body>

</html>