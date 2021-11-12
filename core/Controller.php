<?php

namespace core;

use core\mysql\Variability;
use models\ArticleModel;
use models\sqlModels\SqlArticleModel;
use models\NewsModel;
use models\PageModel;
use entites\User;
use entites\Publish;
use models\sqlModels\SqlNewsModel;

class Controller
{
    protected View $view;
    protected $connect = null;
    protected Variability $variability;

    function __construct()
    {
        $this->view = new View();
        $this->variability = Variability::getInstance();
        $this->connect = $this->variability->chooseVariant();
    }

    /**
     * Открытие модального окна для редактирования пользовтаеля
     */
    protected function edit()
    {
        $window = PageModel::getInstance();
        $user = new User($window->openEditWindow($_POST['indexEdit'], $_POST['id']));
        $this->view->editWindow($user->getLogin(), $user->getEmail(), $user->getDesc());
    }

    /**
     * Открытие модального окна для редактирования сатьи
     */
    protected function editArticle()
    {
        switch (gettype($this->connect)){
            case "array":
                $window = ArticleModel::getInstance();
                $user = new Publish($window->openEditWindowArticle($_POST['indexEdit'], $_POST['id']));
                $this->view->editWindowArticlesAndNews($user->getTitle(), $user->getText());
                break;
            case "object":
                $window = SqlArticleModel::getInstance();
                $user = new Publish($window->openEditWindowArticle($_POST['indexEdit'], $_POST['id']));
                $this->view->editWindowArticlesAndNews($user->getTitle(), $user->getText());
                break;
        }

    }

    /**
     * Открытие модального окна для редактирования новостей
     */
    protected function editNews()
    {
        switch (gettype($this->connect)){
            case "array":
                $window = NewsModel::getInstance();
                $user = new Publish($window->openEditWindowNews($_POST['indexEdit'], $_POST['id']));
                $this->view->editWindowArticlesAndNews($user->getTitle(), $user->getText());
                break;
            case "object":
                $window = SqlNewsModel::getInstance();
                $user = new Publish($window->openEditWindowNews($_POST['indexEdit'], $_POST['id']));
                $this->view->editWindowArticlesAndNews($user->getTitle(), $user->getText());
                break;
        }
    }

}