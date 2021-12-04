<?php

namespace database\Migrations;

use mysqli;

require_once __DIR__ . "/../../enums/Connection.php";

class AllThings
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
    public function putAllThingsTable(): string
    {
        $query = "CREATE TABLE `homestead`.`allThings` (
                                          `id` INT NOT NULL AUTO_INCREMENT,
                                          `subchapter` INT NOT NULL,
                                          `articul` VARCHAR(50) NULL,
                                          `brend` INT NOT NULL,
                                          `model` INT NOT NULL,
                                          `namespace` VARCHAR(50) NULL,
                                          `size` VARCHAR(50) NULL,
                                          `color` INT NOT NULL,
                                          `orientation` VARCHAR(50) NULL,
                                          `chapter` INT NOT NULL,
                                           PRIMARY KEY (`id`),
                                           UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE
                                          )";

        $this->mysqli->query("INSERT INTO homestead.migrations VALUES (null , 'AllThings.php')");

        return $query;
    }

    /**
     * Функция для формирования SQL запроса, и возвращение его в общий класс ролбэка
     */
    public function deleteAllThingsTable(): string
    {
        $query = "DROP TABLE homestead.allThings";

        $this->mysqli->query("DELETE FROM homestead.migrations WHERE `caption` = 'AllThings.php'");

        return $query;
    }
}