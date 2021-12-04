<?php

namespace models\sqlModels;

use core\Model;
use entites\Publish;
use enums\TypeConnect;
use interfaces\News;

class sqlNewsModel extends Model implements News
{
    protected static ?sqlNewsModel $instance = null;

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
    public static function getInstance(): sqlNewsModel
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Добавляем необходимое количество статей для заполнения сайта
     */
    public function getAllNews(): array
    {
        $arrOfNews = [];
        $result = $this->connect->query("select homestead.news.id,
                                                               homestead.users.login as `user`,
                                                               homestead.news.title,
                                                               homestead.news.text,
                                                               homestead.news.date
                                                        from news
                                                        join users
                                                        on news.user_id = users.id
                                                        where ({$this->date} - homestead.news.seconds) < {$this->seconds}");
        while ($row = $result->fetch_assoc()) {
            array_push($arrOfNews, $row);
        }
        return $arrOfNews;
    }

    /**
     * Ручное удаление новости
     */
    public function removeNews(int $indexDel, int $id)
    {
        $this->connect->query("DELETE FROM homestead.news WHERE id = {$id}");
    }

    /**
     * Открытие окна редактирования для новости
     */
    public function openEditWindowNews(int $indexEdit, int $id): array
    {
        return $this->connect->query("select homestead.news.id,
                                                               homestead.users.login as `user`,
                                                               homestead.news.title,
                                                               homestead.news.text,
                                                               homestead.news.date
                                                     from news
                                                     join users
                                                     on news.user_id = users.id
                                                     where homestead.news.id = {$id}")->fetch_assoc();
    }

    /**
     * Редактирование данных новости
     */
    public function edit(Publish $publish)
    {
        $this->connect->query("UPDATE homestead.news 
                                             SET `title` = '{$publish->getTitle()}', `text` = '{$publish->getText()}'
                                             WHERE id = {$publish->getId()}");
    }

    /**
     * Создание новости
     */
    public function newNewsBlock(Publish $publish): array
    {
        $newNews = [
            'title' => $publish->getTitle(),
            'text' => $publish->getText(),
            'user' => $_SESSION['NAME'],
            'date' => $publish->getDate(),
            'seconds' => $publish->getTime(),
        ];

        $arrOfColumns = [];

        $query = "INSERT INTO homestead.news 
                          SET `id` = null,
                              `user_id` = {$_SESSION['id']},";

        $result = $this->connect->query(file_get_contents(__DIR__ . "/../../config/sql/News/columnsNews.sql"));

        while ($columnName = $result->fetch_assoc()) {
            array_push($arrOfColumns, $columnName);
        }

        $arrOfColumns = array_column($arrOfColumns, "COLUMN_NAME");

        $arr = array_intersect(array_keys($newNews), $arrOfColumns);

        foreach ($arr as $el) {
            $query .= "`{$el}` = " . "'{$newNews[$el]}'" . "," . "\n";
        }

        $query = substr($query, 0, -2);

        $this->connect->query($query);
        return $newNews;
    }

    /**
     * Обрабатываем полученные данные из ajax
     * и вытягиваем нужный файл
     */
    public function oldNews(int $index): array
    {
        $result = $this->connect->query("select homestead.news.id,
                                                               homestead.users.login as `user`,
                                                               homestead.news.title,
                                                               homestead.news.text,
                                                               homestead.news.date
                                                        from news
                                                        join users
                                                        on news.user_id = users.id
                                                        where ({$this->date} - homestead.news.seconds) >= {$this->seconds} 
                                                        and homestead.news.id = {$index}")->fetch_assoc();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }

    /**
     * Отображение страниц с пагинацией
     */
    public function pagination(int $page): array
    {
        $news = [];
        $numberStart = $page * $this->countPublishing - $this->countPublishing;
        $result = $this->connect->query("select homestead.news.id,
                                                               homestead.users.login as `user`,
                                                               homestead.news.title,
                                                               homestead.news.text,
                                                               homestead.news.date
                                                        from news
                                                        join users
                                                        on news.user_id = users.id
                                                        where ({$this->date} - homestead.news.seconds) < {$this->seconds} 
                                                        limit {$numberStart} ,{$this->countPublishing}");
        if ($result) {
            while ($new = $result->fetch_assoc()) {
                array_push($news, $new);
            }
        }
        return $news;
    }
}