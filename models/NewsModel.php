<?php

namespace models;

use core\Model;
use entites\Publish;

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
        $date = getdate()[0];
        $arrOfNews = [];
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.News WHERE ({$date} - seconds) < 86400");
                while ($row = $result->fetch_assoc()) {
                    array_push($arrOfNews, $row);
                }
                return $arrOfNews;
            case "array":
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
        if (gettype($this->connect)) {
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
    public function removeNews(int $indexDel)
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.News");
                $allNews = [];
                $delNews = null;
                while ($row = $result->fetch_assoc()) {
                    array_push($allNews, $row);
                }
                foreach (array_values($allNews) as $key => $value) {
                    if ($key === $indexDel) {
                        $delNews = (int)$value['ID'];
                        break;
                    }
                }
                $this->connect->query("DELETE FROM homestead.News WHERE ID = {$delNews}");
                break;
            case "array":
                $this->delete(__DIR__ . $this->connect['file']['news'], $indexDel);
        }
    }

    /**
     * Открытие окна редактирования для новости
     */
    public function openEditWindowNews(int $indexEdit): array
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.News");
                $allNews = [];
                $editID = null;
                while ($article = $result->fetch_assoc()) {
                    array_push($allNews, $article);
                }
                foreach ($allNews as $key => $value) {
                    if ($key === $indexEdit) {
                        $editID = (int)$value['ID'];
                        break;
                    }
                }
                return $this->connect->query("SELECT * FROM homestead.News WHERE ID = '{$editID}'")->fetch_assoc();
            case "array":
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
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.News");
                $allNews = [];
                $editID = null;
                while ($row = $result->fetch_assoc()) {
                    array_push($allNews, $row);
                }
                foreach (array_values($allNews) as $key => $value) {
                    if ($key === $publish->getIndex()) {
                        $editID += (int)$value['ID'];
                        break;
                    }
                }
                $this->connect->query("UPDATE homestead.News SET `title` = '{$publish->getTitle()}', `text` = '{$publish->getText()}' WHERE ID = {$editID}");
                break;
            case "array":
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
            case "object":
                $newNews = [
                    'title' => $publish->getTitle(),
                    'text' => $publish->getText(),
                    'user' => $_SESSION['NAME'],
                    'date' => $publish->getDate(),
                ];
                $query = "INSERT INTO homestead.News VALUES (
                                    null, 
                                     {$_SESSION['id']}, 
                                    '{$publish->getTitle()}', 
                                    '{$publish->getText()}', 
                                    '{$publish->getDate()}',
                                   {$publish->getTime()})";
                $this->connect->query($query);
                return $newNews;
            case "array":
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

    ////

    /**
     * Обрабатываем полученные данные из ajax
     * и вытягиваем нужный файл
     */
    public function oldNews(int $index): array
    {
        $date = getdate()[0];
        switch (gettype($this->connect)) {
            case "object":
                $allOldNews = [];
                $result = $this->connect->query("SELECT * FROM homestead.News WHERE ({$date} - seconds) >= 86400");
                while ($news = $result->fetch_assoc()) {
                    array_push($allOldNews, $news);
                }
                foreach ($allOldNews as $key => $values) {
                    if ($key === $index) {
                        return $values;
                    }
                }
                break;
            case "array":
                $arrNews = $this->helper->myscandir(__DIR__ . $this->connect['file']['oldNews']);
                for ($j = 0; $j < count($arrNews); $j++) {
                    if ($j === $index) {
                        $fileName = __DIR__ . $this->connect['file']['oldNews'] . $arrNews[$j];
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
        $date = getdate()[0];
        switch (gettype($this->connect)) {
            case "object":
                $news = [];
                $numberStart = $page * 6 - 6;
                $result = $this->connect->query("SELECT * FROM homestead.News WHERE ({$date} - seconds) < 86400 LIMIT {$numberStart} ,6");
                while ($new = $result->fetch_assoc()) {
                    array_push($news, $new);
                }
                return $news;
            case "array":
                return $this->generalPagination(__DIR__ . $this->connect['file']['news'], $page);
        }
        return [];
    }
}