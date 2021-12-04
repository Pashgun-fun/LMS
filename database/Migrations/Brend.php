<?php

namespace database\Migrations;

use mysqli;

require_once __DIR__ . "/../../enums/Connection.php";

class Brend
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
    public function putBrendTable(): string
    {
        $query = "CREATE TABLE `homestead`.`brend` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(50) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)";

        $this->mysqli->query("INSERT INTO homestead.migrations VALUES (null , 'Brend.php')");

        return $query;
    }

    /**
     * Функция для формирования SQL запроса, и возвращение его в общий класс ролбэка
     */
    public function deletBrendTable(): string
    {
        $query = "DROP TABLE homestead.brend";

        $this->mysqli->query("DELETE FROM homestead.migrations WHERE `caption` = 'Brend.php'");

        return $query;
    }
}