<?php

namespace database\Migrations;

require_once __DIR__ . "/../../enums/Connection.php";

use mysqli;

class ColumnArticles
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

    public function putColumnArticle(): string
    {
        $query = "alter table homestead.articles
                    add `NewColumn` int null";
        $this->mysqli->query("INSERT INTO homestead.migrations VALUES (null , 'ColumnArticles.php')");
        return $query;
    }

    public function deleteColumnArticle(): string
    {
        $query = "alter table homestead.articles
                  drop column `NewColumn`";

        $this->mysqli->query("DELETE FROM homestead.migrations WHERE `caption` = 'ColumnArticles.php'");

        return $query;
    }
}