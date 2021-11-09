<?php

namespace models;

use core\Model;
use entites\Publish;
use enums\TypeConnect;

class NewsModel extends Model
{
    protected static ?NewsModel $instance = null;

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
    public static function getInstance(): NewsModel
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
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
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
            case TypeConnect::ARRAY_CONNECT:
                return $this->publishing(__DIR__ . $this->connect['file']['news']);
        }
        return [];
    }

    /**
     * Удаление новости, по истечении суток, из ленты
     * Чтобы понять, можно ли удалять файл из базы, мы сравниваем занесенное туда время с текущим
     * Время заносится, когда новость создалась в формате количества секунд с 1970 года
     */
    public function deleteNews(int $time)
    {
        if (gettype($this->connect) === TypeConnect::ARRAY_CONNECT) {
            $arr = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['news']));
            asort($arr);
            $lastFile = ((int)array_pop($arr) + 1);
            foreach ($arr as $val) {
                $fileName = __DIR__ . $this->connect['file']['news'] . $val;
                $infoAboutNew = $this->readFile($fileName);
                $differentTime = +$time - $infoAboutNew['seconds'];
                if ($differentTime >= 86400) {
                    $arrNew = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['oldNews']));
                    asort($arrNew);
                    $lastFile = ((int)array_pop($arrNew));
                    rename(__DIR__ . $this->connect['file']['news'] . $val, __DIR__ . $this->connect['file']['oldNews'] . $lastFile++);
                    unlink(__DIR__ . $this->connect['file']['news'] . $val);
                }
            }
        }
    }

    /**
     * Ручное удаление новости
     */
    public function removeNews(int $indexDel, int $id)
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                $this->connect->query("DELETE FROM homestead.news WHERE id = {$id}");
                break;
            case TypeConnect::ARRAY_CONNECT:
                $this->delete(__DIR__ . $this->connect['file']['news'], $indexDel);
        }
    }

    /**
     * Открытие окна редактирования для новости
     */
    public function openEditWindowNews(int $indexEdit, int $id): array
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                return $this->connect->query("select homestead.news.id,
                                                               homestead.users.login as `user`,
                                                               homestead.news.title,
                                                               homestead.news.text,
                                                               homestead.news.date
                                                     from news
                                                     join users
                                                     on news.user_id = users.id
                                                     where homestead.news.id = {$id}")->fetch_assoc();
            case TypeConnect::ARRAY_CONNECT:
                return $this->openEdit(__DIR__ . $this->connect['file']['news'], $indexEdit);

        }
        return [];
    }

    /**
     * Редактирование данных новости
     */
    public function edit(Publish $publish)
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                $this->connect->query("UPDATE homestead.news 
                                             SET `title` = '{$publish->getTitle()}', `text` = '{$publish->getText()}'
                                             WHERE id = {$publish->getId()}");
                break;
            case TypeConnect::ARRAY_CONNECT:
                $this->editForArticlesAndNews($publish, __DIR__ . $this->connect['file']['news']);
                break;
        }
    }

    /**
     * Создание новости
     */
    public function newNewsBlock(Publish $publish): array
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
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

                $result = $this->connect->query(file_get_contents(__DIR__ . "/../config/sql/News/columnsNews.sql"));

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
            case TypeConnect::ARRAY_CONNECT:
                $arrayFiles = $this->helper->myscandir(__DIR__ . $this->connect['file']['news']);
                asort($arrayFiles);

                $userData = array(
                    'title' => $publish->getTitle(),
                    'text' => $publish->getText(),
                    'user' => $publish->getUser(),
                    'date' => $publish->getDate(),
                    'seconds' => $publish->getTime(),
                    'userID' => $_SESSION['id'],
                );

                $newFile = __DIR__ . $this->connect['file']['news'] . (+array_pop($arrayFiles) + 1);
                $this->writeFile($newFile, $userData);

                return $userData;
        }
        return [];
    }

    /**
     * Обрабатываем полученные данные из ajax
     * и вытягиваем нужный файл
     */
    public function oldNews(int $index): array
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
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
            case TypeConnect::ARRAY_CONNECT:
                $arrNews = $this->helper->myscandir(__DIR__ . $this->connect['file']['news']);
                for ($j = 0; $j < count($arrNews); $j++) {
                    if ($j === $index) {
                        $fileName = __DIR__ . $this->connect['file']['news'] . $arrNews[$j];
                        return $this->readFile($fileName);
                    }
                }
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
            case TypeConnect::ARRAY_CONNECT:
                return $this->generalPagination(__DIR__ . $this->connect['file']['news'], $page);
        }
        return [];
    }
}