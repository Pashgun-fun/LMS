<?php

namespace core;

use core\View;

class Controller
{
    protected View $view;

    function __construct()
    {
        $this->view = new View();
    }
}