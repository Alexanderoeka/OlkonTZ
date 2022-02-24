<?php

namespace Classes;

// Класс подключения к бд
class MysqlClass
{

    // Получаем нашу бд
    public static function myDB()
    {
        $host = '127.0.0.1';
        $username = 'root';
        $password = 'password';
        $dbname = 'tz_olkon'; 


        $mysqli = new \mysqli($host, $username, $password, $dbname);

        if ($mysqli->connect_error) {
            die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }
        return $mysqli;
    }
}
