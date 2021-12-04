<?php

namespace database\Migrations;

use mysqli;

require_once __DIR__ . "/../../enums/Connection.php";

class Users
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
    public function putUsersTable(): array
    {
        $query = "CREATE TABLE `homestead`.`users` (
                                     `id` INT NOT NULL AUTO_INCREMENT,
                                     `login` VARCHAR(45) NULL,
                                     `email` VARCHAR(45) NULL,
                                     `pass` VARCHAR(45) NULL,
                                     `data` VARCHAR(45) NULL,
                                     `descr` VARCHAR(100) NULL,
                                     `role` VARCHAR(100) NULL,

                                     PRIMARY KEY (`id`),
                                     UNIQUE INDEX `ID_UNIQUE` (`id` ASC) VISIBLE)";

        $queryForeignKeyForArticles = "alter table homestead.articles
                                                    add constraint articles
                                                    foreign key (`user_id`) references homestead.users (`id`)";

        $queryForeignKeyForNews = "alter table homestead.news
                                                    add constraint news
                                                    foreign key (`user_id`) references homestead.users (`id`)";

        $arrOfQuery = [
                        $query,
                        $queryForeignKeyForArticles,
                        $queryForeignKeyForNews,
                        ];

        $this->mysqli->query("INSERT INTO homestead.migrations VALUES (null , 'Users.php')");

        return $arrOfQuery;
    }

    /**
     * Функция для формирования SQL запроса, и возвращение его в общий класс ролбэка
     */
    public function deleteUsersTable(): string
    {
        $query = "DROP TABLE homestead.users";

        $this->mysqli->query("DELETE FROM homestead.migrations WHERE caption = 'Users.php'");

        return $query;
    }
}
