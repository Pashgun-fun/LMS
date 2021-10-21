<?php

namespace core;

use controllers\ControllerUser;
use controllers\ControllerPage;
use controllers\ControllerArticle;

class Router extends Rote
{
//    protected static ?Router $_instance = null;

    function __construct()
    {
        parent::__construct();
        $this->path = htmlspecialchars($_SERVER['REQUEST_URI']);
        $this->check();
        $this->run();
    }

//    public static function getInstance(): Router
//    {
//        if (self::$_instance === null) {
//            self::$_instance = new self();
//        }
//        return self::$_instance;
//    }

    private function run()
    {
        switch ($_SERVER['REQUEST_URI']) {
            case "/api/user/login":
                $controllerPage = new ControllerPage();
                $controllerPage->loginPage();
                break;
            case "/api/user/add":
                $controllerUsers = new ControllerUser();
                $controllerUsers->newUser();
                break;
            case "/api/user/delete":
                $controllerUsers = new ControllerUser();
                $controllerUsers->deleteUser();
                break;
            case "/api/user/get":
                $controllerUsers = new ControllerUser();
                $controllerUsers->allUsers();
                break;
            case "/api/window/edit":
                $controllerPage = new ControllerPage();
                $controllerPage->editPage();
                break;
            case "/api/user/sort":
                $controllerUsers = new ControllerUser();
                $controllerUsers->sortUsers();
                break;
            case "/api/user/edit":
                $controllerUsers = new ControllerUser();
                $controllerUsers->editUser();
                break;
            case "/api/user/exit":
                $controllerUsers = new ControllerUser();
                $controllerUsers->exitFromProfile();
                break;
            case "/api/user/role":
                $controllerUsers = new ControllerUser();
                $controllerUsers->authorization();
                break;
            case "/":
                $controllerUsers = new ControllerUser();
                $controllerUsers->checkRole();
                break;
            case "/api/articles":
                $controllerArticle = new ControllerArticle();
                $controllerArticle->printAllArticles();
                break;
        }
    }

}