<?php

namespace Middlewares;

// Класс проверки что пользователь вошел 
class Auth
{
    // Проверка аутентификации
    public function checkauth()
    {



        if (!isset($_SESSION['user'])) {


            header("Location: http://w91715mr.beget.tech/?class=main&method=login");
            exit();
        }
    }
}
