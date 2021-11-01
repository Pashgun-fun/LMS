<?php

namespace models;

use core\Model;

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
    public function openEditWindow(int $indexEdit): array
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.Users");
                $allUsers = [];
                $editID = null;
                while ($row = $result->fetch_assoc()) {
                    array_push($allUsers, $row);
                }
                foreach (array_values($allUsers) as $key => $value) {
                    if ($key === $indexEdit) {
                        $editID = (int)$value['ID'];
                        break;
                    }
                }
                return $this->connect->query("SELECT * FROM homestead.Users WHERE ID = '{$editID}'")->fetch_assoc();
            case "array":
                return $this->openEdit(__DIR__ . $this->connect['file']['users'], $indexEdit);
        }
        return [];
    }
}