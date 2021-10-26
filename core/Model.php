<?php

namespace core;

use entites\Publish;

class Model
{
    /**
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

    /**
     * Добавляем необходимое количество статей для заполнения сайта
     */
    protected function publishRandom(string $dir, string $config)
    {
        /**
         * Данные для скелетона статьи берутся из config`a
         */
        $config = require_once $config;
        /**
         * Далее сканируется дирректория, где будут размещаться новые статьи
         * Сортируется, чтобы наибольший индекс файла был послденим в списке
         * Получаем значение последнего элемента, который и будет являться максимальным индексом
         * Создаем новый индекс для новго файла на еденицу больше предыдущего
         */
        $arrFiles = $this->helper->myscandir($dir);
        asort($arrFiles);
        $lastFile = ((int)array_pop($arrFiles) + 1);
        $j = 0;
        /**
         * СОздаём новые файлы в том колибесвте, сколшько нам необходимо
         */
        while ($j <= 25) {
            $index = $lastFile + $j;
            $fileName = $dir . $index;
            $this->writeFile($fileName, $config['articles']);
            $j++;
        }
    }

    /**
     * Вывод списка всех статей из базы данных
     */
    protected function publishing(string $dir): array
    {
        $arrayArticles = array();
        /**
         * Сканируем дирректорию с файлами для статей, и преобразуем в массив
         */
        $arr = array_values($this->helper->myscandir($dir));
        foreach ($arr as $val) {
            $fileName = $dir . $val;
            $file = $this->readFile($fileName);
            array_push($arrayArticles, $file);
        }
        return $arrayArticles;
    }

    /**
     * Удаляеем статью из базы данных
     * Путем сканироования и далее нахоэждения общего индекса
     */
    protected function delete(string $dir, int $indexDel)
    {
        $arr = array_values($this->helper->myscandir($dir));
        asort($arr);
        $file = null;
        for ($j = 0; $j < count($arr); $j++) {
            if ($j === $indexDel) {
                $file = $arr[$j];
                break;
            }
        }
        unlink($dir . $file);
    }

    /**
     * Открытие окна редактирования
     */
    protected function openEdit(string $dir, int $index)
    {
        /**
         * Сканируем дирректорию с файламии, которые хотим редактировать
         */
        $arr = array_values($this->helper->myscandir($dir));
        asort($arr);
        $file = null;
        for ($j = 0; $j < count($arr); $j++) {
            if ($j === $index) {
                $file = $arr[$j];
                break;
            }
        }
        $fileEdit = $dir . $file;
        return $this->readFile($fileEdit);
    }

    /**
     * Редактирование новостей и статей
     */
    protected function editForArticlesAndNews($publish, $dir)
    {
        $arr = array_values($this->helper->myscandir($dir));
        asort($arr);
        $fileEdit = null;
        for ($j = 0; $j < count($arr); $j++) {
            if ($j === $publish->getIndex()) {
                $fileEdit = $arr[$j];
                break;
            }
        }

        $file = $dir . $fileEdit;
        $el = $this->readFile($file);

        $el['user'] = $publish->getUser();
        $el['title'] = $publish->getTitle();
        $el['text'] = $publish->getText();

        file_put_contents($dir . $fileEdit, '');
        file_put_contents($dir . $fileEdit, json_encode($el));
    }

}