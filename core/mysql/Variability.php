<?php

namespace core\mysql;

class Variability
{
    public $variant = null;

    function __construct()
    {
        $this->variant = require_once __DIR__ . "/../../public/config/mysql_config/variability.php";
    }

    public function chooseVariant()
    {
        switch ($this->variant) {
            case "file":
                return require_once __DIR__ . "/../../public/config/directories.php";
            case "base":
                $dataConnectToBase = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
                return new \mysqli($dataConnectToBase['base']['hostname'], $dataConnectToBase['base']['username'], $dataConnectToBase['base']['password'], $dataConnectToBase['base']['database']);
        }
    }
}