<?php

namespace core;

class Rote
{
    protected string $path;

    function __construct()
    {
        $this->path = $_SERVER['REQUEST_URI'];
    }

    public function check()
    {
        switch ($this->path) {
            case "/api/user/login":
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === 'user') {
                    header("Location: /");
                }
                break;
            case "/api/user/add":
            case "/api/user/delete":
            case "/api/user/edit":
            case "/api/window/edit":
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === 'user') {
                    header("Location: /");
                }
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                break;
            case "/api/user/get":
            case "/api/user/exit":
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                break;
        }
    }
}
