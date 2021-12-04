<?php

namespace database\Seeds;

use mysqli;
require_once __DIR__."/../../enums/Connection.php";


class CreateNews
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
    public function getNews()
    {

        $arrOfColumns = [];
        $arrOfKeys = [];

        $result = $this->mysqli->query(file_get_contents(__DIR__ . "/../../config/sql/News/columnsNews.sql"));

        while ($columnName = $result->fetch_assoc()) {
            array_push($arrOfColumns, $columnName);
        }

        foreach ($this->dataArticles['news'] as $key => $value) {
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

        $query = "INSERT INTO homestead.news
                  SET `id` = null ,
                      `user_id` = 1,
                      ";

        foreach ($arr as $key => $value) {
            if ($value === 'id') {
                continue;
            }
            if ($value === "seconds") {
                $query .= "`seconds` = {$this->dataArticles['news']['seconds']}";
            } else {
                $query .= "`{$value}` = " . "'{$this->dataArticles['news'][$value]}'" . "," . "\n";
            }
        }

        for ($j = 0; $j < 25; $j++) {
            $this->mysqli->query($query);
        }
        $this->mysqli->commit();
    }
}