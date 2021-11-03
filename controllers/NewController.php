<?php

namespace controllers;

use core\Controller;
use core\Validation;
use core\Helper;
use models\NewsModel;
use entites\Publish;

class NewController extends Controller
{
    protected Helper $helper;
    protected NewsModel $newModel;

    function __construct()
    {
        parent::__construct();
        $this->newModel = NewsModel::getInstance();
        $this->helper = new Helper();
    }

    /**
     * Вывод новостей с валидацией
     * Создаётся сущность и через геттеры выводим данные
     * Обрезаем длину текста до 100 символов
     * Для обычных пользователей и гостей
     */
    public function printShortsNews()
    {
        $valid = new Validation();
        $art = $this->newModel->getAllNews();
        foreach ($art as $val) {
            $article = new Publish($val);
            $this->view->news(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                "User",
                $article->getDate()
            );
        }
    }

    /**
     * Вывод новостей с валидацией
     * Создаётся сущность и через геттеры выводим данные
     * Обрезаем длину текста до 100 символов
     * Для админов
     */
    public function printShortNewsForAdmin()
    {
        $valid = new Validation();
        $art = $this->newModel->getAllNews();
        foreach ($art as $val) {
            $article = new Publish($val);
            $this->view->newAdmin(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                "User",
                $article->getDate()
            );
        }

    }

    /**
     * Вывод полной новости по нужному индексу
     */
    public function printAllArticles()
    {
        $art = $this->newModel->getAllNews();
        $article = new Publish($art[$_POST['index']]);
        $this->view->cardArticle(
            $article->getTitle(),
            $article->getText(),
            $article->getUser(),
            $article->getDate()
        );
    }

    /**
     * Удаление новости по истечении суток
     */
    public function deleteNews()
    {
        $arr = $this->helper->resetAPI();
        $this->newModel->deleteNews((int)$arr['seconds']);
    }

    /**
     * Ручное удаление новости
     */
    public function removeNews()
    {
        $this->newModel->removeNews((int)$_POST['indexDel'], (int)$_POST['id']);
    }

    /**
     * Открытие окна редактирования для новости
     */
    public function windowEdit()
    {
        $this->editNews();
    }

    /**
     * Редактирование информации в новости с валидацией по полям из конфига
     */
    public function editNewsInfo(): void
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkArticlesAndNewsFields');
        if (count($arr) !== 0) {
            return;
        }
        $article = new Publish($_POST['arr']);
        $this->newModel->edit($article);
    }

    /**
     * Добавление нового длока новостей в общий список
     * Для обычных  пользователей
     */
    public function newNews(): void
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkArticlesAndNewsFields');
        if (count($arr) !== 0) {
            return;
        }
        $publish = new Publish($_POST['arr']);
        $userName = $this->newModel->newNewsBlock($publish);
        $this->view->news(
            $userName['title'],
            $valid->checkLengthArticle($userName['text']),
            $userName['user'],
            $userName['date']
        );
    }

    /**
     * Добавление нового длока новостей в общий список
     * Для админов
     */
    public function newNewsAdmin(): void
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkArticlesAndNewsFields');
        if (count($arr) !== 0) {
            return;
        }
        $publish = new Publish($_POST['arr']);
        $userName = $this->newModel->newNewsBlock($publish);

        $this->view->newAdmin(
            $userName['title'],
            $valid->checkLengthArticle($userName['text']),
            $userName['user'],
            $userName['date']
        );
    }

    /**
     * Достаем старую новость по хэшу
     */
    public function getOldNews()
    {
        $data = $this->newModel->oldNews($_POST['index']);
        if (empty($data)) {
            return;
        }
        $news = new Publish($data);
        $this->view->cardArticle($news->getTitle(), $news->getText(), $news->getUser(), $news->getDate());
    }

    /**
     * Отображение страниц с пагинацией с новостями
     * для обычных пользоватлей и гостей
     */
    public function pagination()
    {
        $valid = new Validation();
        $articleBlock = $this->newModel->pagination($_POST['page']);
        foreach ($articleBlock as $val) {
            $article = new Publish($val);
            $this->view->news(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                $article->getUser(),
                $article->getDate(),
                $article->getId()
            );
        }
    }

    /**
     * Отображение страниц с пагинацией с новостями
     * для админов
     */
    public function paginationAdmin()
    {
        $valid = new Validation();
        $articleBlock = $this->newModel->pagination($_POST['page']);
        if (empty($articleBlock)) {
            return;
        }
        foreach ($articleBlock as $val) {
            $article = new Publish($val);
            $this->view->newAdmin(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                $article->getUser(),
                $article->getDate(),
                $article->getId()
            );
        }
    }

}