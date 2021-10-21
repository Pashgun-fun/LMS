<?php

namespace core;

class Validation
{
    /**
     * @param array $data
     * @param string $fields
     * @return array
     * Валидация обязательных полей, проверка на заполнение, которые записаны в конфиге
     * Если эти поля не завполнены создание пользователя произойдет безуспешно, как и его редактирование
     **/
    public function checkCreateForm(array $data, string $fields): array
    {
        $listErrors = array();
        $config = require_once __DIR__ . "/../public/config/validation_check.php";
        foreach ($config[$fields] as $value) {
            if (!isset($data[$value])) {
                $listErrors[$value] = "Поле обязательно к заполнению";
            }
        }
        return $listErrors;
    }
}
