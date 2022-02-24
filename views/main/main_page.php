<?php


echo isset($_SESSION['message']['login']) ? $_SESSION['message']['login'] : '' . '<br>';
echo isset($_SESSION['message']['password']) ? $_SESSION['message']['password'] : '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Main</title>


</head>

<body>
    <h2>MENU</h2>
    <?php


    foreach ($_SESSION['menu'] as $menu) {

    ?>
        <input type="button" value="<?= $menu ?>">
    <?
    }

    unset($_SESSION['menu']);

    ?>


    <br>
    <br>
    <br>
    <a href="/?class=main&method=logout">Logout</a>
</body>

</html>