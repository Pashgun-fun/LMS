<?php

namespace database;

require_once __DIR__ . "/../core/Autoloader.php";

use database\Seeds\CreateArticles;
use database\Seeds\CreateNews;
use database\Seeds\CreateUsers;

class Seeds
{
    public CreateArticles $createArticles;
    public CreateNews $createNews;
    public CreateUsers $createUsers;

    function __construct()
    {
        $this->createArticles = new CreateArticles();
        $this->createNews = new CreateNews();
        $this->createUsers = new CreateUsers();
    }

    /**
     * Заполнение данными таблицы
     */
    public function seeds()
    {
        switch ($_SERVER['argv'][1]) {
            case "articles":
                $this->createArticles->getArticles();
                break;
            case "news":
                $this->createNews->getNews();
                break;
            case "users":
                $this->createUsers->getUsers();
                break;
            case "all":
                $this->createUsers->getUsers();
                $this->createArticles->getArticles();
                $this->createNews->getNews();
                break;
        }
    }
}

$seed = new Seeds();
$seed->seeds();