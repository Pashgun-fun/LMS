<?php

namespace core\mysql;

use enums\Connection;
use enums\TypeConnect;

class Variability
{
    public $variant = null;

    protected static ?Variability $instance = null;
    public Connection $connection;

    public static function getInstance(): Variability
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct()
    {
        $this->variant = require_once __DIR__ . "/../../config/mysql_config/variability.php";
        $this->connection = Connection::getInstance();
    }

    /**
     * Функция выполняющее подлкючение к базе данных если в конфиге предан тип подключения БД
     * В этом случае возвращается объект подключения к БД
     * Если тип подключения к файлам, возвращаем пути к локальным БД
     * В этом случае возвращается ассоциативный массив
     */
    public function chooseVariant()
    {
        switch ($this->variant['typeToConnect']) {
            case TypeConnect::FILE:
                return require __DIR__ . "/../../config/directories.php";
            case TypeConnect::MYSQL:
            default:
                return new \mysqli(
                    $this->connection->getHostname(),
                    $this->connection->getUsername(),
                    $this->connection->getPassword(),
                    $this->connection->getDatabase()
                );
        }
    }
}