<?php

namespace models;

use core\Model;
use entites\Publish;

class ArticleModel extends Model
{

    protected static ?ArticleModel $instance = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Закрываем подключение к БД
     */
    public function __destruct()
    {
        switch (gettype($this->connect)) {
            case "object":
            {
                $this->connect->close();
            }
        }
    }

    /**
     * Singleton
     * Чтобы объект не создавалася несолько раз один и тот же
     * а использовался один и тот же, если он уже создан
     */
    public static function getInstance(): ArticleModel
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Вывод списка всех статей из базы данных
     */
    public function getAllArticles(): array
    {
        $arrOfArticles = [];
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.Articles");
                while ($row = $result->fetch_assoc()) {
                    array_push($arrOfArticles, $row);
                }
                return $arrOfArticles;
            case "array":
                return $this->publishing(__DIR__ . $this->connect['file']['articles']);
        }
        return [];
    }

    /**
     * Чтение полной статьи
     */
    public function readAllArticles(): array
    {
        switch (gettype($this->connect)) {
            case "object":
                $articles = [];
                $result = $this->connect->query("SELECT * FROM homestead.Articles");
                while ($article = $result->fetch_assoc()) {
                    array_push($articles, $article);
                }
                return $articles;
            case "array":
                return $this->publishing(__DIR__ . $this->connect['file']['articles']);
        }
        return [];
    }

    /**
     * Удаляеем статью из базы данных
     * Путем сканироования и далее нахоэждения общего индекса
     */
    public function deleteArticle(int $indexDel)
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.Articles");
                $allArticles = [];
                $delArticle = null;
                while ($row = $result->fetch_assoc()) {
                    array_push($allArticles, $row);
                }
                foreach (array_values($allArticles) as $key => $value) {
                    if ($key === $indexDel) {
                        $delArticle = (int)$value['ID'];
                        break;
                    }
                }
                $this->connect->query("DELETE FROM homestead.Articles WHERE ID = {$delArticle}");
                break;
            case "array":
                $this->delete(__DIR__ . $this->connect['file']['articles'], $indexDel);
        }
    }

    /**
     * Открытие окна редактирования для статьи
     */
    public function openEditWindowArticle(int $indexEdit): array
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.Articles");
                $allArticles = [];
                $editID = null;
                while ($article = $result->fetch_assoc()) {
                    array_push($allArticles, $article);
                }
                foreach ($allArticles as $key => $value) {
                    if ($key === $indexEdit) {
                        $editID = (int)$value['ID'];
                        break;
                    }
                }
                return $this->connect->query("SELECT * FROM homestead.Articles WHERE ID = '{$editID}'")->fetch_assoc();
            case "array":
                return $this->openEdit(__DIR__ . $this->connect['file']['articles'], $indexEdit);
        }
        return [];
    }

    /**
     * Редактирование данных статьи
     */
    public function edit(Publish $publish)
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.Articles");
                $allArticles = [];
                $editID = null;
                while ($row = $result->fetch_assoc()) {
                    array_push($allArticles, $row);
                }
                foreach (array_values($allArticles) as $key => $value) {
                    if ($key === $publish->getIndex()) {
                        $editID += (int)$value['ID'];
                        break;
                    }
                }
                $this->connect->query("UPDATE homestead.Articles SET `title` = '{$publish->getTitle()}', `text` = '{$publish->getText()}' WHERE ID = {$editID}");
                break;
            case "array":
                $this->editForArticlesAndNews($publish, __DIR__ . $this->connect['file']['articles']);
        }
    }

    /**
     * Создание новой статьи
     */
    public function newArticleBlock(Publish $publish): array
    {
        switch (gettype($this->connect)) {
            case "object":
                $newArticle = [
                    'title' => $publish->getTitle(),
                    'text' => $publish->getText(),
                    'user' => $_SESSION['NAME'],
                    'date' => $publish->getDate(),

                ];
                $query = "INSERT INTO homestead.Articles VALUES (
                                    null, 
                                     {$_SESSION['id']}, 
                                    '{$publish->getTitle()}', 
                                    '{$publish->getText()}', 
                                    '{$publish->getDate()}')";
                $this->connect->query($query);
                return $newArticle;
            case "array":
                $arrayFiles = $this->helper->myscandir(__DIR__ . $this->connect['file']['articles']);
                asort($arrayFiles);

                $userData = array(
                    'title' => $publish->getTitle(),
                    'text' => $publish->getText(),
                    'user' => $publish->getUser(),
                    'date' => $publish->getDate(),
                    'userID' => $_SESSION['id'],
                );

                $newFile = __DIR__ . $this->connect['file']['articles'] . (+array_pop($arrayFiles) + 1);
                $this->writeFile($newFile, $userData);

                return $userData;
        }
        return [];
    }

    /**
     * Отображение страниц с пагинацией
     */
    public function pagination(int $page): array
    {
        switch (gettype($this->connect)) {
            case "object":
                $articles = [];
                $numberStart = $page * 6 - 6;
                $result = $this->connect->query("SELECT * FROM homestead.Articles LIMIT {$numberStart} ,6");
                while ($article = $result->fetch_assoc()) {
                    array_push($articles, $article);
                }
                return $articles;
            case "array":
                return $this->generalPagination(__DIR__ . $this->connect['file']['articles'], $page);
        }
        return [];
    }
}