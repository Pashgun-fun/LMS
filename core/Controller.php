<?php

namespace core;

use models\ArticleModel;
use models\NewModel;
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
        $window = new PageModel();
        $user = new User($window->openEditWindow($_POST['indexEdit']));
        $this->view->editWindow($user->getLogin(), $user->getEmail(), $user->getDesc());
    }

    /**
     * Открытие модального окна для редактирования сатьи
     */
    protected function editArticle()
    {
        $window = new ArticleModel();
        $user = new Publish($window->openEditWindowArticle($_POST['indexEdit']));
        $this->view->editWindow($user->getUser(), $user->getTitle(), $user->getText());
    }

    /**
     * Открытие модального окна для редактирования новостей
     */
    protected function editNews(){
        $window = new NewModel();
        $user = new Publish($window->openEditWindowNews($_POST['indexEdit']));
        $this->view->editWindow($user->getUser(), $user->getTitle(), $user->getText());
    }

}