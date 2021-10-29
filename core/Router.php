<?php

namespace core;

use controllers\NewController;
use controllers\UserController;
use controllers\PageController;
use controllers\ArticleController;
use enums\Roles;
use core\Middleware;

class Router
{
    protected static ?Router $_instance = null;
    protected Middleware $middleware;
    protected Roles $roles;

    function __construct()
    {
        $this->middleware = new Middleware();
        $this->middleware->check();
        $this->roles = new Roles();
        $this->run();
    }

    /**
     * Принцип singleton
     * Суть такая, что если объект уже существует при повторном его вызове не будет
     * создаваться новый объект, а будет работа с тем же
     */
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
            case "/api/article/read":
                $controllerArticle = new ArticleController();
                $controllerArticle->printAllArticles();
                break;
            case "/api/article/delete":
                $controllerArticle = new ArticleController();
                $controllerArticle->deleteArticle();
                break;
            case "/api/news/read":
                $controllerNews = new NewController();
                $controllerNews->printAllArticles();
                break;
            case "/api/check/news":
                $controllerNews = new NewController();
                $controllerNews->deleteNews();
                break;
            case "/api/news/delete":
                $controllerNews = new NewController();
                $controllerNews->removeNews();
                break;
            case "/api/window/edit/article":
                $controllerArticle = new ArticleController();
                $controllerArticle->windowEdit();
                break;
            case "/api/article/edit":
                $controllerArticle = new ArticleController();
                $controllerArticle->editArticleInfo();
                break;
            case "/api/window/edit/news":
                $controllerNews = new NewController();
                $controllerNews->windowEdit();
                break;
            case "/api/news/edit":
                $controllerNews = new NewController();
                $controllerNews->editNewsInfo();
                break;
            case "/api/news/old":
                $controllerNews = new NewController();
                $controllerNews->getOldNews();
                break;
            case "/":
                $controllerUsers = new UserController();
                $controllerUsers->checkRole();
                break;
        }
    }

}