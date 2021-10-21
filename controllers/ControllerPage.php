<?php

namespace controllers;

use core\Controller;
use models\PageModel;
use models\User;

class ControllerPage extends Controller
{
    public function loginPage()
    {
        $this->view->login();
    }

    /**
     * Открытие модального окна для редактирования пользователя
     * Заполнение данных пользователя при клике на него
     **/
    public function editPage()
    {
        $window = new PageModel();
        $user = new User($window->openEditWindow($_POST['indexEdit']));
        $this->view->editWindow($user->getLogin(), $user->getEmail(), $user->getDesc());
    }
}