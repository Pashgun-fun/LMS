<?php

namespace core;

use entites\Publish;
use core\mysql\Variability;
use enums\General;

class Model
{
    protected $connect = null;
    protected Variability $variability;
    protected Helper $helper;
    public int $seconds;
    public int $countPublishing;
    public int $date;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->variability = Variability::getInstance();
        $this->connect = $this->variability->chooseVariant();
        $this->seconds = General::TIME_INTERVAL_FOR_NEWS;
        $this->countPublishing = General::NUMBER_OF_OUTPUT_PUBLICATION;
        $this->date = getdate()[0];
    }

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
    protected function openEdit(string $dir, int $index): array
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
    protected function editForArticlesAndNews(Publish $publish, string $dir)
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

        $el['title'] = $publish->getTitle();
        $el['text'] = $publish->getText();

        file_put_contents($dir . $fileEdit, '');
        file_put_contents($dir . $fileEdit, json_encode($el));
    }

    /**
     * Модель для обработки пагинации страниц статей и новостей
     */
    protected function generalPagination(string $dir, int $page)
    {
        $countPage = ceil(count($this->publishing($dir)) / $this->countPublishing);
        if (empty($this->publishing($dir))) {
            return [];
        }
        $subarray = [];
        for ($i = 0; $i <= $countPage; $i++) {
            $subarray[$i] = array_slice(
                $this->publishing($dir),
                $i * $this->countPublishing,
                $this->countPublishing
            );
        }
        return $subarray[$page - 1];
    }

}