<?php



namespace Classes;

// Класс ввода данных в бд
class Insert
{

    // Функция ввода стандартных данных пользователей
    // Без нее в базе не будет юзеров 
    // а значит и авторизоваться на сайте нельзя
    public function queryinsertdb()
    {

        $result = MysqlClass::myDB()->query("SELECT COUNT(*) AS `count`FROM `users` ");

        $count = $result->fetch_assoc()['count'];
        echo $count;
        echo '<br>';

        if ($count == 0) {
            // Хешируем пароли 
            $userPass = password_hash('user', PASSWORD_DEFAULT);
            $dmitriPass = password_hash('qwer1', PASSWORD_DEFAULT);
            $sergeiPass = password_hash('qwer12', PASSWORD_DEFAULT);

            $sql = "INSERT INTO `users` (`login`,`password`,`privilege`,`email`) VALUES 
    ('userone','$userPass',1,'userone@mail.ru'), 
    ('dmitriFedorov','$dmitriPass',2,'dmitri@mail.ru'),
    ('sergeiKoshelev','$sergeiPass',3,'sergei@mail.ru')";

            $resultOfQuery = MysqlClass::myDB()->query($sql);

            if ($resultOfQuery) {
                echo '<br> Data have loaded in DB!';
            } else {
                echo '<br> Data have not loaded in DB! Something wrong! ';
            }
        } else {
            echo 'The data is already there! No need do this';
        }
    }
}
