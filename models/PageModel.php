<?php

namespace models;

use core\Model;

class PageModel extends Model
{
    public $directory = null;

    public function __construct()
    {
        $this->directory = __DIR__ . "/../database/";
    }

    /**
     * @param int $indexEdit
     * @return array|null
     * Передача в контроллер данных пользователя, которые собираемся редактировать
     * Происходит работа с файлом этого пользователя, которые берется по его уникальному индексу
     **/
    public function openEditWindow(int $indexEdit): ?array
    {
        $file = $this->directory . $indexEdit . '.txt';
        return $this->readFile($file);
    }
}