<?php

namespace core;

use models\PageModel;
use entites\User;

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

}