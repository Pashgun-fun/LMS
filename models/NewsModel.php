<?php

namespace models;

use core\Model;
use entites\Publish;
use interfaces\News;

class NewsModel extends Model implements News
{
    protected static ?NewsModel $instance = null;

    public function __construct()
    {
        parent::__construct();
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
        return $this->publishing(__DIR__ . $this->connect['file']['news']);
    }

    /**
     * Удаление новости, по истечении суток, из ленты
     * Чтобы понять, можно ли удалять файл из базы, мы сравниваем занесенное туда время с текущим
     * Время заносится, когда новость создалась в формате количества секунд с 1970 года
     */
    public function deleteNews(int $time)
    {
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

    /**
     * Ручное удаление новости
     */
    public function removeNews(int $indexDel, int $id)
    {
        $this->delete(__DIR__ . $this->connect['file']['news'], $indexDel);
    }

    /**
     * Открытие окна редактирования для новости
     */
    public function openEditWindowNews(int $indexEdit, int $id): array
    {
        return $this->openEdit(__DIR__ . $this->connect['file']['news'], $indexEdit);
    }

    /**
     * Редактирование данных новости
     */
    public function edit(Publish $publish)
    {
        $this->editForArticlesAndNews($publish, __DIR__ . $this->connect['file']['news']);
    }

    /**
     * Создание новости
     */
    public function newNewsBlock(Publish $publish): array
    {
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

    /**
     * Обрабатываем полученные данные из ajax
     * и вытягиваем нужный файл
     */
    public function oldNews(int $index): array
    {
        $arrNews = $this->helper->myscandir(__DIR__ . $this->connect['file']['news']);
        for ($j = 0; $j < count($arrNews); $j++) {
            if ($j === $index) {
                $fileName = __DIR__ . $this->connect['file']['news'] . $arrNews[$j];
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
        return $this->generalPagination(__DIR__ . $this->connect['file']['news'], $page);
    }
}