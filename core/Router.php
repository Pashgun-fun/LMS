<?php

namespace core;

use controllers\NewController;
use controllers\UserController;
use controllers\PageController;
use controllers\ArticleController;

class Router extends Rote
{
    protected static ?Router $_instance = null;

    function __construct()
    {
        parent::__construct();
        $this->path = htmlspecialchars($_SERVER['REQUEST_URI']);
        $this->check();
        $this->run();
    }

    public static function getInstance(): Router
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function run()
    {
        switch ($_SERVER['REQUEST_URI']) {
            case "/api/user/login":
                $controllerPage = new PageController();
                $controllerPage->loginPage();
                break;
            case "/api/user/add":
                $controllerUsers = new UserController();
                $controllerUsers->newUser();
                break;
            case "/api/user/delete":
                $controllerUsers = new UserController();
                $controllerUsers->deleteUser();
                break;
            case "/api/user/get":
                $controllerUsers = new UserController();
                $controllerUsers->allUsers();
                break;
            case "/api/window/edit":
                $controllerPage = new PageController();
                $controllerPage->editPage();
                break;
            case "/api/user/edit":
                $controllerUsers = new UserController();
                $controllerUsers->editUser();
                break;
            case "/api/user/exit":
                $controllerUsers = new UserController();
                $controllerUsers->exitFromProfile();
                break;
            case "/api/user/role":
                $controllerUsers = new UserController();
                $controllerUsers->authorization();
                break;
            case "/api/articles":
                $controllerArticle = new ArticleController();
                $controllerArticle->printShortsArticles();
                break;
            case "/api/article/read":
                $controllerArticle = new ArticleController();
                $controllerArticle->printAllArticles();
                break;
            case "/api/article/random":
                $controllerArticle = new ArticleController();
                $controllerArticle->getRandomArticles();
                break;
            case "/api/article/delete":
                $controllerArticle = new ArticleController();
                $controllerArticle->deleteArticle();
                break;
            case "/api/news":
                $controllerNews = new NewController();
                $controllerNews->printShortsNews();
                break;
            case "/api/news/read":
                $controllerNews = new NewController();
                $controllerNews->printAllArticles();
                break;
            case "/api/news/random":
                $controllerNews = new NewController();
                $controllerNews->getRandomNews();
                break;
            case "/":
                $controllerUsers = new UserController();
                $controllerUsers->checkRole();
                break;
        }
    }

}