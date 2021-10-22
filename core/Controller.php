<?php

namespace core;

use core\View;
use models\Article;
use models\ArticleModel;
use models\PageModel;
use models\User;

class Controller
{
    protected View $view;

    function __construct()
    {
        $this->view = new View();
    }

    protected function edit()
    {
        $window = new PageModel();
        $user = new User($window->openEditWindow($_POST['indexEdit']));
        $this->view->editWindow($user->getLogin(), $user->getEmail(), $user->getDesc());
    }
}