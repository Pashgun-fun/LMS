<?php

namespace core\mysql;

class Variability
{
    public $variant = null;

    function __construct()
    {
        $this->variant = require_once __DIR__ . "/../../public/config/mysql_config/variability.php";
    }

    /**
     * Функция выполняющее подлкючение к базе данных если в конфиге предан тип подключения БД
     * В этом случае возвращается объект подключения к БД
     * Если тип подключения к файлам, возвращаем пути к локальным БД
     * В этом случае возвращается ассоциативный массив
     */
    public function chooseVariant()
    {
        switch ($this->variant) {
            case "file":
                return require_once __DIR__ . "/../../public/config/directories.php";
            case "base":
            default:
                $dataConnectToBase = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
                return new \mysqli($dataConnectToBase['base']['hostname'], $dataConnectToBase['base']['username'], $dataConnectToBase['base']['password'], $dataConnectToBase['base']['database']);
        }
    }
}