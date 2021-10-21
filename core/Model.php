<?php

namespace core;

class Model
{
    /**
     * @param string $file
     * @return array
     * Метод используемый в большинстве моделей
     * Необходим для чтения данных файла
     * Преобразование данных в ассоциативный массив
     * Принимает файл, с которым надо провести операции
     **/
    protected function readFile(string $file): array
    {
        $db = fopen($file, 'a+');
        $read = trim(fread($db, filesize($file)));
        $el = json_decode($read, true);
        return $el;
    }

    /**
     * @param string $newFile
     * @param array $userData
     * Записываем данные в файл
     * Применятеся для создания нового пользователя
     * Создается новый файл и записывается в него данные зарегестрированного пользователя
     **/
    protected function writeFile(string $newFile, array $userData)
    {
        $db = fopen($newFile, 'a+');
        $str = json_encode($userData);
        fwrite($db, $str);
    }
}