<?php


namespace Classes;


// Класс восстановления пароля через почту
class Changepassword
{

    // Загружает страничку ввода почты для сброса пароля
    public function recoverpassword()
    {

        include 'views/change_password/recoverPassword.html';
    }


    // Функция отправки сообщения на введенную почту
    public function sendmail()
    {



        // Проверка что email введён
        if ($_POST['email']) {
            $email = $_POST['email'];

            // хешируем хеш, который состоит из email и времени
            $hash = password_hash($email . time(), PASSWORD_DEFAULT);

            // Переменная $headers нужна для Email заголовка
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "To: <$email>\r\n";
            $headers .= "From: <mail@example.com>\r\n";
            // Сообщение для Email
            $message = "
                <html>
                <head>
                <title>Подтвердите Email</title>
                </head>
                <body>
                <p>Что бы восстановить пароль перейдите по <a href=\"http://example.com/?class=ChangePassword&method=checkhashpass&hash=\"{$hash}\">ссылка</a></p>
                </body>
                </html>
                ";

            // Меняем хеш в БД
            $sql = "UPDATE `user` SET hash='$hash' WHERE email='$email'";
            MysqlClass::myDB()->query($sql);
            // проверка отправилась ли почта
            if (mail($email, "Recover password Email", $message, $headers)) {
                // Если да, то выводит сообщение
                echo 'Referens for recover password sent on email!';
            } else {
                echo 'something wrong!';
            }
        } else {
            // Если ошибка есть, то выводить её 
            echo "You didnt enter your email!";
        }
    }


    // Проверяет хеш(происходит после того как пользователь перешел по ссылке на почте)
    public function checkhashpass()
    {


        // Проверка есть ли хеш
        if ($_REQUEST['hash']) {
            // Кладём этот хеш в отдельную переменную 
            $hash = $_REQUEST['hash'];
            // Проверка на то, что есть пользователь с таки хешом
            if ($result = MysqlClass::myDB()->query("SELECT `id` FROM `user` WHERE `hash`='" . $hash . "'")) {

                session_start();
                $_SESSION['changePasswordHash'] = $hash;
                include 'views/change_password/changePass.php';
            }
        } else {
            echo "something wrong!";
        }
    }

    // Последняя стадия изменения пароля. Пароль изменяется,
    // либо выводит ошибку 
    public function passwordAcceptance()
    {


        // Если нет хеша в сессии,то пароль поменять нельзя (зашита)
        if (!isset($_SESSION['changePasswordHash'])) {
            header('Location: http://w91715mr.beget.tech/');
            exit();
        }

        $pass1 = $_POST['pass1'];

        $pass2 = $_POST['pass2'];

        // Если пароли не совпадают, то пользователя редиректит, и просят ввести пароль ещё раз 
        // Если совпадает, то пароль меняется
        if ($pass1 == $pass2) {

            // По хешу меняется пароль в таблице пользователей
            $sql = "UPDATE INTO `users` SET `password` = '{$pass2}' WHERE `hash` = '{$_SESSION['changePasswordHash']}'";

            $newPassQuery = MysqlClass::myDB()->query($sql);

            if ($newPassQuery) {
                echo 'You changed your password! Well done!';
                header('Location: http://w91715mr.beget.tech/');
                exit();
            } else {
                echo 'something wrong';
            }
        } else {

            $_SESSION['errorPass'] = 'passwords dont match!';

            header('Location: http://w91715mr.beget.tech/?class=Changepassword&method=checkhashpass');
            exit();
        }
    }
}
