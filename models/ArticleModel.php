<?php

namespace models;

use core\Model;
use core\Helper;
use entites\Publish;

class ArticleModel extends Model
{
    public string $directory;
    public string $config;

    public Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/Articles/";
        $this->config = __DIR__ . "/../public/config/random_articles_and_news.php";
    }


    public function pagination(array $list, $page): array
    {
        $arrOfPages = array_chunk($list, $page);
        return $arrOfPages;
    }

    /**
     * Вывод списка всех статей из базы данных
     */
    public function getAllArticles(int $page): array
    {
        $_SESSION['PAGES'] = 9;
        $countPerPage = ceil(count($this->publishing($this->directory)) / 9);

        if (count($this->publishing($this->directory)) > $countPerPage) {
            return $this->pagination($this->publishing($this->directory), $countPerPage)[$page - 1];
        }
        return $this->publishing($this->directory);
    }

    /**
     * Чтение полной статьи
     */
    public function readAllArticles(): array
    {
        return $this->publishing($this->directory);
    }

    /**
     * Добавляем необходимое количество статей для заполнения сайта
     */
    public function setRandomArticles()
    {
        $this->publishRandom($this->directory, $this->config);
    }

    /**
     * Удаляеем статью из базы данных
     * Путем сканироования и далее нахоэждения общего индекса
     */
    public function deleteArticle(int $indexDel)
    {
        $this->delete($this->directory, $indexDel);
    }

    /**
     * Открытие окна редактирования для статьи
     */
    public function openEditWindowArticle(int $indexEdit): array
    {
        return $this->openEdit($this->directory, $indexEdit);
    }

    /**
     * Редактирование данных статьи
     */
    public function edit(Publish $publish)
    {
        $this->editForArticlesAndNews($publish, $this->directory);
    }

    /**
     * Создание новой статьи
     */
    public function newArticleBlock(Publish $publish): array
    {
        $arrayFiles = $this->helper->myscandir($this->directory);
        asort($arrayFiles);

        $userData = array(
            'title' => $publish->getTitle(),
            'text' => $publish->getText(),
            'user' => $publish->getUser(),
            'date' => $publish->getDate(),
        );

        $newFile = $this->directory . (+array_pop($arrayFiles) + 1);
        $this->writeFile($newFile, $userData);

        return $userData;
    }
}