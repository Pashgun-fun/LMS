<?php

namespace enums;


class Connection
{

    public static ?Connection $instance = null;
    public array $config;

    public static function getInstance(): ?Connection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct()
    {
        $this->config = require_once __DIR__ . "/../config/mysql_config/config_database.php";
    }

    public function getIp(): string
    {
        return $this->config['base']['ip'];
    }

    public function getUsername(): string
    {
        return $this->config['base']['username'];
    }

    public function getHostname(): string
    {
        return $this->config['base']['hostname'];
    }

    public function getPassword(): string
    {
        return $this->config['base']['password'];
    }

    public function getDatabase(): string
    {
        return $this->config['base']['database'];
    }
}