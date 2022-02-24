<?php

namespace Classes;


require_once 'middlewares/Auth.php';
require_once 'classes/MysqlClass.php';

use Middlewares\Auth;
use Classes\MysqlClass;

// Основной класс
class Main
{



    public function __construct()
    {
    }

    // Функция вызова главной страницы
    public function mainpage()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        // Проверка аутентификации
        $auth = new Auth();
        $auth->checkAuth();

        // Проверка прав пользователя
        $this->rightsVerification();

        // Вывод главной страницы
        include 'views/main/main_page.php';
    }


    // Функция проверки прав пользователя  + взятие данных кнопок из бд
    public function rightsverification()
    {



        // Берет те данные кнопок, у которых есть соответствующие привелении с пользователем
        $sql = "SELECT `title` FROM `menu` WHERE `privilege` LIKE '%{$_SESSION['user']['privilege']}%'";

        $menuQuery = MysqlClass::myDB()->query($sql);

        // Берет все кнопки, к которым пользователь имеет доступ по привелегиям 
        while ($row = $menuQuery->fetch_assoc()) {

            $_SESSION['menu'][] = $row['title'];
        }
    }


    // Функция входа 
    public function login()
    {



        session_start();


        // Если пользователь в системе, то его редиректит в индекном файле на главную страницу
        if (isset($_SESSION['user'])) {
            header('Location: http://w91715mr.beget.tech/');
        }

        // Вывод страницы входа 
        include('views/main/login.php');
    }


    // Функция проверки данных при попытке входа
    public function checklogin()
    {




        session_start();

        // Взятие данных из запроса из формы 
        $login = $_POST['login'];

        $password = $_POST['password'];


        // Сообщение, которое выведется пользователю
        $message = array();



        // Считаем сколько запесей с похожими именами
        $count = MysqlClass::myDB()->query("SELECT COUNT(*) AS `count` FROM `users` WHERE `login` LIKE '%{$login}%'")->fetch_assoc()['count'];
        // Если записей слишком много(более 1000), то мы в любом случае не выводим 
        // что пользователь немного опечатался(ввел почти правильный логин ) 
        if ($count < 1000) {

            // Делаем запрос, берем всех пользователей с похожим логином
            $loginsQuery = MysqlClass::myDB()->query("SELECT `login`, `password`, `privilege` FROM `users` WHERE `login` LIKE '%{$login}%'");

            while ($row = $loginsQuery->fetch_assoc()) {

                // Сравниваем введенный логин с логином из бд
                similar_text($row['login'], $login, $percent);

                // Если совпадение равно или более 80%, то
                // можем вывести что пользователь почти правильно ввел логин
                if ($percent >= 80) {


                    // Если же пароль, и пользователь полностю совпадают, то входим в профиль 
                    // С сообщением об успешном входе
                    if ($percent == 100 && password_verify($password, $row['password'])) {



                        $_SESSION['user']['login'] =  $login;
                        // Помещаем хешированны пароль
                        $_SESSION['user']['password'] = $row['password'];
                        $_SESSION['user']['privilege'] = $row['privilege'];



                        $message['login'] = 'Your login correct!';
                        $message['password'] = 'Password correct! Login was successful!';

                        $_SESSION['message'] = $message;
                        // Редирект на главную страницу
                        header('Location: http://w91715mr.beget.tech/?class=main&method=mainpage');
                        exit();
                    } else if ($percent == 100) {
                        // Если же совпадает полностью только логин, а пороль нет

                        $message['login'] = 'Your login have found!';
                        $message['password'] = 'The password is incorrect!';

                        break;
                    }

                    $message['login'] = 'You made a little typo in the login';
                    break;
                }
            }

            // При других ситуациях, просто выводим что логин не верный

            if (!isset($message['login'])) {
                $message['login'] = 'This login does not exist';
            }
        }

        $_SESSION['message'] = $message;



        // Происходит редирект на вход в учетную запись
        header('Location: http://w91715mr.beget.tech/');
    }



    // Функция выхода из профиля
    public function logout()
    {
        session_start();
        unset($_SESSION['user']);
        header('Location: http://w91715mr.beget.tech/');
    }
}
