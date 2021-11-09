<?php

namespace models;

use core\Model;
use entites\Publish;
use enums\General;
use enums\TypeConnect;

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
            case TypeConnect::OBJECT_CONNECT:
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
            case TypeConnect::OBJECT_CONNECT:
                $result = $this->connect->query(file_get_contents(__DIR__ . "/../config/sql/Articles/allArticles.sql"));
                while ($row = $result->fetch_assoc()) {
                    array_push($arrOfArticles, $row);
                }
                return $arrOfArticles;
            case TypeConnect::ARRAY_CONNECT:
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
            case TypeConnect::OBJECT_CONNECT:
                $articles = [];
                $query = "select homestead.articles.id,
                                  homestead.users.login as `user`,
                                  homestead.articles.title,
                                  homestead.articles.text,
                                  homestead.articles.date
                          from articles
                          join users
                          on articles.user_id = users.id";
                $result = $this->connect->query($query);
                while ($article = $result->fetch_assoc()) {
                    array_push($articles, $article);
                }
                return $articles;
            case TypeConnect::ARRAY_CONNECT:
                return $this->publishing(__DIR__ . $this->connect['file']['articles']);
        }
        return [];
    }

    /**
     * Удаляеем статью из базы данных
     * Путем сканироования и далее нахоэждения общего индекса
     */
    public function deleteArticle(int $indexDel, int $id)
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                $this->connect->query("DELETE FROM homestead.articles WHERE id = {$id}");
                break;
            case TypeConnect::ARRAY_CONNECT:
                $this->delete(__DIR__ . $this->connect['file']['articles'], $indexDel);
        }
    }

    /**
     * Открытие окна редактирования для статьи
     */
    public function openEditWindowArticle(int $indexEdit, int $id): array
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                return $this->connect->query("select homestead.articles.id,
                                                              homestead.users.login as `user`,
                                                              homestead.articles.title,
                                                              homestead.articles.text,
                                                              homestead.articles.date
                                                      from articles
                                                      join users
                                                      on articles.user_id = users.id 
                                                      where homestead.articles.id = {$id}")->fetch_assoc();
            case TypeConnect::ARRAY_CONNECT:
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
            case TypeConnect::OBJECT_CONNECT:
                $this->connect->query("UPDATE homestead.articles 
                                             SET `title` = '{$publish->getTitle()}', `text` = '{$publish->getText()}' 
                                             WHERE id = {$publish->getId()}");
                break;
            case TypeConnect::ARRAY_CONNECT:
                $this->editForArticlesAndNews($publish, __DIR__ . $this->connect['file']['articles']);
                break;
        }
    }

    /**
     * Создание новой статьи
     */
    public function newArticleBlock(Publish $publish): array
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                $newArticle = [
                    'title' => $publish->getTitle(),
                    'text' => $publish->getText(),
                    'user' => $_SESSION['NAME'],
                    'date' => $publish->getDate(),

                ];

                $arrOfColumns = [];

                $query = "INSERT INTO homestead.articles 
                          SET `id` = null,
                              `user_id` = {$_SESSION['id']},";

                $result = $this->connect->query(file_get_contents(__DIR__ . "/../config/sql/Articles/columnsArticles.sql"));

                while ($columnName = $result->fetch_assoc()) {
                    array_push($arrOfColumns, $columnName);
                }

                $arrOfColumns = array_column($arrOfColumns, "COLUMN_NAME");

                $arr = array_intersect(array_keys($newArticle), $arrOfColumns);

                foreach ($arr as $el) {
                    $query .= "`{$el}` = " . "'{$newArticle[$el]}'" . "," . "\n";
                }

                $query = substr($query, 0, -2);

                $this->connect->query($query);
                return $newArticle;
            case TypeConnect::ARRAY_CONNECT:
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
            case TypeConnect::OBJECT_CONNECT:
                $articles = [];
                $numberStart = $page * $this->countPublishing - $this->countPublishing;
                $query = "select homestead.articles.id,
                                  homestead.users.login as `user`,
                                  homestead.articles.title,
                                  homestead.articles.text,
                                  homestead.articles.date
                          from articles
                          join users
                          on articles.user_id = users.id
                          limit {$numberStart} ,{$this->countPublishing}";
                $result = $this->connect->query($query);
                if ($result) {
                    while ($article = $result->fetch_assoc()) {
                        array_push($articles, $article);
                    }
                }
                return $articles;
            case TypeConnect::ARRAY_CONNECT:
                return $this->generalPagination(__DIR__ . $this->connect['file']['articles'], $page);
        }
        return [];
    }
}