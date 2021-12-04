<?php

namespace database\Migrations;

use mysqli;

require_once __DIR__ . "/../../enums/Connection.php";

class News
{
    public \enums\Connection $connection;
    public mysqli $mysqli;

    public function __construct()
    {
        $this->connection = \enums\Connection::getInstance();
        $this->mysqli = new mysqli(
            $this->connection->getIp(),
            $this->connection->getUsername(),
            $this->connection->getPassword(),
            $this->connection->getDatabase()
        );
    }
    /**
     * Функция для формирования SQL запроса, и возвращение его в общий класс миграций
     */
    public function putNewsTable(): string
    {
        $query = "CREATE TABLE `homestead`.`news` (
                                      `id` INT NOT NULL AUTO_INCREMENT,
                                      `user_id` INT NULL,
                                      `title` VARCHAR(45) NULL,
                                      `text` text NULL,
                                      `date` VARCHAR(45) NULL,
                                      `seconds` bigint NULL,
                                      PRIMARY KEY (`id`),
                                      UNIQUE INDEX `ID_UNIQUE` (`id` ASC) VISIBLE)";

        $this->mysqli->query("INSERT INTO homestead.migrations VALUES (null , 'News.php')");

        return $query;
    }

    /**
     * Функция для формирования SQL запроса, и возвращение его в общий класс ролбэка
     */
    public function deleteNewsTable(): string
    {
        $query = "DROP TABLE homestead.news";

        $this->mysqli->query("DELETE FROM homestead.migrations WHERE caption = 'News.php'");

        return $query;
    }
}