<?php

namespace database\Seeds;

use mysqli;

require_once __DIR__."/../../enums/Connection.php";

class CreateArticles
{

    public array $dataArticles;
    public \enums\Connection $connection;
    public mysqli $mysqli;

    function __construct()
    {
        $this->connection = \enums\Connection::getInstance();
        $this->dataArticles = require __DIR__ . "/../../config/random_articles_and_news.php";
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

    /**
     * Автоматическое формирование запроса для создания новостей
     */
    public function getArticles()
    {
        $arrOfColumns = [];
        $arrOfKeys = [];

        $result = $this->mysqli->query(file_get_contents(__DIR__ . "/../../config/sql/Articles/columnsArticles.sql"));

        while ($columnName = $result->fetch_assoc()) {
            array_push($arrOfColumns, $columnName);
        }

        foreach ($this->dataArticles['articles'] as $key => $value) {
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

        $query = "INSERT INTO homestead.articles
              SET `id` = null ,
                  `user_id` = 1 ,
                  ";

        foreach ($arr as $key => $value) {
            if ($value === 'id') {
                continue;
            }
            $query .= "`{$value}` = " . "'{$this->dataArticles['articles'][$value]}'" . "," . "\n";
        }

        /**
         * Удаление двух последних символов у запроса, которые всегда будут являться символ запятой и новой строки
         */
        $query = substr($query, 0, -2);

        for ($j = 0; $j < 25; $j++) {
            $this->mysqli->query($query);
        }
    }
}


