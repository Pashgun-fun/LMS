<?php

namespace core;

use models\ArticleModel;
use models\NewsModel;
use models\PageModel;
use entites\User;
use entites\Publish;

class Controller
{
    protected View $view;

    function __construct()
    {
        $this->view = new View();
    }

    /**
     * Открытие модального окна для редактирования пользовтаеля
     */
    protected function edit()
    {
        $window = PageModel::getInstance();
        $user = new User($window->openEditWindow($_POST['indexEdit']));
        $this->view->editWindow($user->getLogin(), $user->getEmail(), $user->getDesc());
    }

    /**
     * Открытие модального окна для редактирования сатьи
     */
    protected function editArticle()
    {
        $window = ArticleModel::getInstance();
        $user = new Publish($window->openEditWindowArticle($_POST['indexEdit']));
        $this->view->editWindowArticlesAndNews("User", $user->getTitle(), $user->getText());
    }

    /**
     * Открытие модального окна для редактирования новостей
     */
    protected function editNews()
    {
        $window = NewsModel::getInstance();
        $user = new Publish($window->openEditWindowNews($_POST['indexEdit']));
        $this->view->editWindowArticlesAndNews("User", $user->getTitle(), $user->getText());
    }

}