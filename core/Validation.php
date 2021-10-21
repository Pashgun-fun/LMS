<?php

namespace core;

class Validation
{
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
