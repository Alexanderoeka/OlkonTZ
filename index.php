<?php

// Индексный файл, основной вход на сайт
// Тут происходит и маршрутизация

// Подключение классов
require_once 'classes/Main.php';
require_once 'middlewares/Auth.php';
require_once 'classes/Insert.php';
require_once 'classes/Changepassword.php';


// Подключение классов
use Classes\Changepassword;
use Middlewares\Auth;
use Classes\Main;
use Classes\Insert;


//  Вывод ошибок 
error_reporting(E_ALL);
ini_set("display_errors", 1);



// Получаем имя класса из url
$class = isset($_REQUEST['class']) ? ucfirst($_REQUEST['class']) : 'Main';

// Получаем имя метода из url
$method = isset($_REQUEST['method']) ? $_REQUEST['method'] : 'mainpage';


// Тк все основные классы находятся в области "Classes"
// то здесь происходит добавление соответствующего префикса
$class = "Classes\\" . $class;



// Создаем объект класса
$object = new $class();


// Вызываем функцию класса
$object->$method();
