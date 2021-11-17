<?php

namespace models;

use core\Model;
use entites\Publish;
use interfaces\Articles;

class ArticleModel extends Model implements Articles
{

    protected static ?ArticleModel $instance = null;

    public function __construct()
    {
        parent::__construct();
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
        return $this->publishing(__DIR__ . $this->connect['file']['articles']);
    }

    /**
     * Чтение полной статьи
     */
    public function readAllArticles(): array
    {
        return $this->publishing(__DIR__ . $this->connect['file']['articles']);
    }

    /**
     * Удаляеем статью из базы данных
     * Путем сканироования и далее нахоэждения общего индекса
     */
    public function deleteArticle(int $indexDel, int $id)
    {
        $this->delete(__DIR__ . $this->connect['file']['articles'], $indexDel);
    }

    /**
     * Открытие окна редактирования для статьи
     */
    public function openEditWindowArticle(int $indexEdit, int $id): array
    {
        return $this->openEdit(__DIR__ . $this->connect['file']['articles'], $indexEdit);
    }

    /**
     * Редактирование данных статьи
     */
    public function editArticle(Publish $publish)
    {
        $this->editForArticlesAndNews($publish, __DIR__ . $this->connect['file']['articles']);
    }

    /**
     * Создание новой статьи
     */
    public function newArticleBlock(Publish $publish): array
    {
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

    /**
     * Отображение страниц с пагинацией
     */
    public function pagination(int $page): array
    {
        return $this->generalPagination(__DIR__ . $this->connect['file']['articles'], $page);
    }
}