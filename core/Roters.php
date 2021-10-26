<?php

namespace core;

class Roters
{
    protected string $path;

    function __construct()
    {
        $this->path = $_SERVER['REQUEST_URI'];
    }

    /**
     * Функиця для порверки прав доступа
     */
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
            case "/api/article/delete":
            case "/api/news/delete":
            case "/api/window/edit/article":
            case "/api/article/edit":
            case "/api/window/edit/news":
            case "/api/news/edit":
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === 'user') {
                    header("Location: /");
                }
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                break;
            case "/api/user/get":
            case "/api/user/exit":
            case "/api/news/add":
            case "/api/article/add":
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                break;
        }
    }
}
