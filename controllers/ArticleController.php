<?php

namespace controllers;

use core\Controller;
use core\Helper;
use entites\Publish;
use models\ArticleModel;
use core\Validation;
use models\sqlModels\SqlArticleModel;

class ArticleController extends Controller
{
    protected $articleModel;
    protected Helper $helper;

    function __construct()
    {
        parent::__construct();
        $this->helper = new Helper();
        switch (gettype($this->connect)) {
            case "array":
                $this->articleModel = ArticleModel::getInstance();
                break;
            case "object":
                $this->articleModel = SqlArticleModel::getInstance();
                break;
        }
    }

    /**
     * Вывод статей с валидацией
     * Создаётся сущность и через геттеры выводим данные
     * Обрезаем длину текста до 100 символов
     * Для обычных пользователей и гостей
     */
    public function printShortsArticles()
    {
        $valid = new Validation();
        $art = $this->articleModel->getAllArticles();
        foreach ($art as $val) {
            $article = new Publish($val);
            $this->view->article(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                "User",
                $article->getDate(),
            );

        }
    }

    /**
     * Вывод статей с валидацией
     * Создаётся сущность и через геттеры выводим данные
     * Обрезаем длину текста до 100 символов
     * Для админов
     */
    function printShortArticlesForAdmin()
    {
        $valid = new Validation();
        $art = $this->articleModel->getAllArticles();
        foreach ($art as $val) {
            $article = new Publish($val);
            $this->view->articleAdmin(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                "User",
                $article->getDate(),
            );
        }

    }

    /**
     * Вывод полной статьи по нужному индексу
     */
    public function printAllArticles()
    {
        $art = $this->articleModel->readAllArticles();
        $article = new Publish($art[$_POST['index']]);
        $this->view->cardArticle(
            $article->getTitle(),
            $article->getText(),
            $article->getUser(),
            $article->getDate()
        );
    }


    /**
     * Удаление статьи
     */
    public function deleteArticle()
    {
        $this->articleModel->deleteArticle((int)$_POST['indexDel'], (int)$_POST['id']);
    }

    /**
     * Открытие окна редактирования для статей
     */
    public function windowEdit()
    {
        $this->editArticle();
    }

    /**
     * Редактирование статьи с валидпцией по полям из конфига
     */
    public function editArticleInfo(): void
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkArticlesAndNewsFields');
        if (count($arr) !== 0) {
            return;
        }
        $article = new Publish($_POST['arr']);
        $this->articleModel->editArticle($article);
    }

    /**
     * Добавление новой статьи в список
     * Для обычных пользователей
     */
    public function newArticle(): void
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkArticlesAndNewsFields');
        if (count($arr) !== 0) {
            return;
        }
        $publish = new Publish($_POST['arr']);
        $userName = $this->articleModel->newArticleBlock($publish);
        $this->view->article(
            $userName['title'],
            $valid->checkLengthArticle($userName['text']),
            $userName['user'],
            $userName['date']
        );
    }

    /**
     * Добавление новой статьи в список
     * Для обычных админов
     */
    public function newArticleAdmin(): void
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkArticlesAndNewsFields');
        if (count($arr) !== 0) {
            return;
        }
        $publish = new Publish($_POST['arr']);
        $userName = $this->articleModel->newArticleBlock($publish);
        $this->view->articleAdmin(
            $userName['title'],
            $valid->checkLengthArticle($userName['text']),
            $userName['user'],
            $userName['date']
        );
    }

    /**
     * Отображение страниц с пагинацией
     * для обычных пользоватлей и гостей
     */
    public function pagination()
    {
        $valid = new Validation();
        $articleBlock = $this->articleModel->pagination($_POST['page']);
        foreach ($articleBlock as $val) {
            $article = new Publish($val);
            $this->view->article(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                $article->getUser(),
                $article->getDate(),
                $article->getId()
            );
        }
    }

    /**
     * Отображение страниц с пагинацией
     * для админов
     */
    public function paginationAdmin()
    {
        $valid = new Validation();
        $articleBlock = $this->articleModel->pagination($_POST['page']);
        if (empty($articleBlock)) {
            return;
        }
        foreach ($articleBlock as $val) {
            $article = new Publish($val);
            $this->view->articleAdmin(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                $article->getUser(),
                $article->getDate(),
                $article->getId()
            );
        }
    }
}