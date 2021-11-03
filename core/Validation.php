<?php

namespace core;

use enums\General;

class Validation
{
    /**
     * Валидация обязательных полей, проверка на заполнение, которые записаны в конфиге
     * Если эти поля не завполнены создание пользователя произойдет безуспешно, как и его редактирование
     **/
    public function checkCreateForm(array $data, string $fields): array
    {
        $listErrors = array();
        $config = include __DIR__ . "/../config/validation_check.php";
        foreach ($config[$fields] as $value) {
            if (!isset($data[$value])) {
                $listErrors[$value] = "Поле обязательно к заполнению";
            }
        }
        return $listErrors;
    }

    /**
     * Проверка длины строки и обрезка ее
     * Слова не обрезаются по середине,а последнее слово вставляется полностью
     */
    public function checkLengthArticle(string $str): string
    {
        if (strlen($str) > General::LENGTH_OF_PUBLISHING) {
            $sum = 0;
            $arrWords = [];
            /**
             * Входная строка разбивается на элементы массива
             */
            $arrOfWords = explode(" ", $str);
            /**
             * Итеррируем массив, на каждой итерации длину слова добавляем в общую сумму сиволов
             * Далее проверяем,больше ли длина строки необходимой для нас длины
             * Если нет, то в общий массив кладём это влово и двигаемся дальше
             * Иначе выходим из функции и возвращаем сокращенный массив
             */
            for ($j = 0; $j < count($arrOfWords); $j++) {
                $sum += strlen($arrOfWords[$j]);
                if ($sum > General::LENGTH_OF_PUBLISHING) {
                    return implode(" ", $arrWords) . " ...";
                }
                array_push($arrWords, $arrOfWords[$j]);
            }
        }
        return $str;
    }
}
