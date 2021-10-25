<?php

namespace models;

use core\Helper;
use core\Model;

class PageModel extends Model
{
    public $directory = null;
    public Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/Users/";
    }

    /**
     * Передача в контроллер данных пользователя, которые собираемся редактировать
     * Происходит работа с файлом этого пользователя, которые берется по его уникальному индексу
     **/
    public function openEditWindow(int $indexEdit): array
    {
        return $this->openEdit($this->directory, $indexEdit);
    }
}