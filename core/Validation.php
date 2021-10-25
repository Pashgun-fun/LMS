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

    public function checkLengthArticle($str)
    {
        $sum = 0;
        $arrWords = [];
        $arrOfWords = explode(" ", $str);
        for ($j = 0; $j < count($arrOfWords); $j++) {
            $sum += strlen($arrOfWords[$j]);
            if ($sum > 90) {
                return implode(" ", $arrWords)." ...";
            }
            array_push($arrWords, $arrOfWords[$j]);
        }
    }
}
