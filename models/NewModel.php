<?php

namespace models;

use core\Model;
use core\Helper;
use entites\Publish;

class NewModel extends Model
{
    public string $newDirectory;
    public string $directory;
    public string $config;

    public Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/News/";
        $this->config = __DIR__ . "/../public/config/random_articles_and_news.php";
        $this->newDirectory = __DIR__ . "/../database/OldNews/";
    }

    /**
     * Добавляем необходимое количество статей для заполнения сайта
     */
    public function getAllNews(): array
    {
        return $this->publishing($this->directory);
    }

    /**
     * Удаление новости, по истечении суток, из ленты
     * Чтобы понять, можно ли удалять файл из базы, мы сравниваем занесенное туда время с текущим
     * Время заносится, когда новость создалась в формате количества секунд с 1970 года
     */
    public function deleteNews(int $time)
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        asort($arr);
        $lastFile = ((int)array_pop($arr) + 1);
        foreach ($arr as $val) {
            $fileName = $this->directory . $val;
            $infoAboutNew = $this->readFile($fileName);
            $differentTime = +$time - $infoAboutNew['seconds'];
            if ($differentTime >= 86400) {
                $arrNew = array_values($this->helper->myscandir($this->newDirectory));
                asort($arrNew);
                $lastFile = ((int)array_pop($arrNew));
                rename($this->directory . $val, $this->newDirectory . $lastFile++);
                unlink($this->directory . $val);
            }
        }
    }

    /**
     * Ручное удаление новости
     */
    public function removeNews(int $indexDel)
    {
        $this->delete($this->directory, $indexDel);
    }

    /**
     * Открытие окна редактирования для новости
     */
    public function openEditWindowNews(int $indexEdit): array
    {
        return $this->openEdit($this->directory, $indexEdit);
    }

    /**
     * Редактирование данных новости
     */
    public function edit(Publish $publish)
    {
        $this->editForArticlesAndNews($publish, $this->directory);
    }

    /**
     * Создание новости
     */
    public function newNewsBlock(Publish $publish): array
    {
        $arrayFiles = $this->helper->myscandir($this->directory);
        asort($arrayFiles);

        $userData = array(
            'title' => $publish->getTitle(),
            'text' => $publish->getText(),
            'user' => $publish->getUser(),
            'date' => $publish->getDate(),
            'time' => $publish->getTime(),
        );

        $newFile = $this->directory . (+array_pop($arrayFiles) + 1);
        $this->writeFile($newFile, $userData);

        return $userData;
    }

    /**
     * Обраатываем полученные данные из ajax
     * и вытягиваем нужный файл
     */
    public function oldNews(int $index): array
    {
        $arrNews = $this->helper->myscandir($this->newDirectory);
        for ($j = 0; $j < count($arrNews); $j++) {
            if ($j === $index) {
                $fileName = $this->newDirectory . $arrNews[$j];
                return $this->readFile($fileName);
            }
        }
        return [];
    }

    /**
     * Отображение страниц с пагинацией
     */
    public function pagination(int $page): array
    {
        return $this->generalPagination($this->directory, $page);
    }
}