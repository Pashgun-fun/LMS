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

    /**
     * Вывод списка всех статей из базы данных
     */
    public function getAllArticles(): array
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

    public function openEditWindowArticle(int $indexEdit): array
    {
        return $this->openEdit($this->directory, $indexEdit);
    }

    public function edit(Publish $publish)
    {
        $this->editForArticlesAndNews($publish, $this->directory);
    }
}