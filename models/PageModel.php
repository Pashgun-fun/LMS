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
    public function openEditWindow(int $indexEdit): ?array
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        asort($arr);
        $file = null;
        for ($j = 0; $j < count($arr); $j++) {
            if ($j === $indexEdit) {
                $file = $arr[$j];
                break;
            }
        }
        $fileEdit = $this->directory . $file;
        return $this->readFile($fileEdit);
    }
}