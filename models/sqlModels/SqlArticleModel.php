<?php

namespace models\sqlModels;

use core\Model;
use enums\TypeConnect;
use entites\Publish;
use interfaces\Articles;

class SqlArticleModel extends Model implements Articles
{
    protected static ?SqlArticleModel $instance = null;
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
    public static function getInstance(): SqlArticleModel
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
        $result = $this->connect->query(file_get_contents(__DIR__ . "/../../config/sql/Articles/allArticles.sql"));
        while ($row = $result->fetch_assoc()) {
            array_push($arrOfArticles, $row);
        }
        return $arrOfArticles;
    }

    /**
     * Чтение полной статьи
     */
    public function readAllArticles(): array
    {
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
    }

    /**
     * Удаляеем статью из базы данных
     * Путем сканироования и далее нахоэждения общего индекса
     */
    public function deleteArticle(int $indexDel, int $id)
    {
        $this->connect->query("DELETE FROM homestead.articles WHERE id = {$id}");
    }

    /**
     * Открытие окна редактирования для статьи
     */
    public function openEditWindowArticle(int $indexEdit, int $id): array
    {
        return $this->connect->query("select homestead.articles.id,
                                                              homestead.users.login as `user`,
                                                              homestead.articles.title,
                                                              homestead.articles.text,
                                                              homestead.articles.date
                                                      from articles
                                                      join users
                                                      on articles.user_id = users.id 
                                                      where homestead.articles.id = {$id}")->fetch_assoc();
    }

    /**
     * Редактирование данных статьи
     */
    public function editArticle(Publish $publish)
    {
        $this->connect->query("UPDATE homestead.articles 
                                     SET `title` = '{$publish->getTitle()}', `text` = '{$publish->getText()}' 
                                     WHERE id = {$publish->getId()}");
    }

    /**
     * Создание новой статьи
     */
    public function newArticleBlock(Publish $publish): array
    {
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

        $result = $this->connect->query(file_get_contents(__DIR__ . "/../../config/sql/Articles/columnsArticles.sql"));

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
    }

    /**
     * Отображение страниц с пагинацией
     */
    public function pagination(int $page): array
    {
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
    }
}