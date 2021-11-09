<?php

namespace models;

use core\Model;
use enums\TypeConnect;

class PageModel extends Model
{
    protected static ?PageModel $instance = null;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Singleton
     * Чтобы объект не создавалася несолько раз один и тот же
     * а использовался один и тот же, если он уже создан
     */
    public static function getInstance(): PageModel
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Передача в контроллер данных пользователя, которые собираемся редактировать
     * Происходит работа с файлом этого пользователя, которые берется по его уникальному индексу
     **/
    public function openEditWindow(int $indexEdit, int $id): array
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                return $this->connect->query("SELECT * FROM homestead.users WHERE id = $id")->fetch_assoc();
            case TypeConnect::ARRAY_CONNECT:
                return $this->openEdit(__DIR__ . $this->connect['file']['users'], $indexEdit);
        }
        return [];
    }
}