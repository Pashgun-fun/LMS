<?php

namespace database\Seeds;

use mysqli;

require_once __DIR__ . "/../../enums/Connection.php";

class CreateUsers
{
    public array $dataArticles;
    public \enums\Connection $connection;
    public mysqli $mysqli;

    function __construct()
    {
        $this->connection = \enums\Connection::getInstance();
        $this->dataArticles = require __DIR__ . "/../../config/random_users.php";
        $this->mysqli = new mysqli(
            $this->connection->getIp(),
            $this->connection->getUsername(),
            $this->connection->getPassword(),
            $this->connection->getDatabase()
        );
    }

    /**
     * Закрытие соединения с БД
     */
    function __destruct()
    {
        $this->mysqli->close();
    }

    public function getUsers()
    {
        $arrOfColumns = [];
        $arrOfKeys = [];

        $result = $this->mysqli->query(file_get_contents(__DIR__ . "/../../config/sql/Users/columnsUsers.sql"));

        while ($columnName = $result->fetch_assoc()) {
            array_push($arrOfColumns, $columnName);
        }

        foreach ($this->dataArticles as $key => $value) {
            array_push($arrOfKeys, $key);
        }

        /**
         * Формирование одного общего массива,
         * состоящего из вложенных, по ключам
         */
        $arrOfColumns = array_column($arrOfColumns, "COLUMN_NAME");

        /**
         * Нахождение общего из двух массивов
         */
        $arr = array_intersect($arrOfKeys, $arrOfColumns);

        /**
         * Что будет общее для всех запросов
         */
        $query = "INSERT INTO homestead.users
              SET `id` = null ,
                  ";

        /**
         * Специальный запрос для создания админа
         */
        $queryForAdmin = "INSERT INTO homestead.users
                          SET `id` = null ,
                          `login` = 'Admin', 
                          `email` = 'Admin', 
                          `pass` = '123', 
                          `data` = '01.01.2000', 
                          `descr` = 'Hello', 
                          `role` = 'admin'";

        foreach ($arr as $key => $value) {
            $query .= "`{$value}` = " . "'{$this->dataArticles[$value]}'" . "," . "\n";
        }

        /**
         * Удаление двух последних символов у запроса, которые всегда будут являться символ запятой и новой строки
         */
        $query = substr($query, 0, -2);

        for ($j = 0; $j < 25; $j++) {
            if ($j === 0){
                $this->mysqli->query($queryForAdmin);
            }
            $this->mysqli->query($query);
        }
    }
}