<?php

namespace controllers;

use core\Controller;

class PageController extends Controller
{
    /**
     * Загрузка страницы авторизации
     */
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
       $this->edit();
    }
}