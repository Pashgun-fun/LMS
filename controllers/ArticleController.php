<?php

namespace controllers;

use core\Controller;
use core\Helper;
use entites\Publish;
use models\ArticleModel;
use core\Validation;

class ArticleController extends Controller
{
    protected ArticleModel $articleModel;
    protected Helper $helper;

    function __construct()
    {
        parent::__construct();
        $this->articleModel = new ArticleModel();
        $this->helper = new Helper();
    }

    /**
     * Вывод статей с валидацией
     * Создаётся сущность и через геттеры выводим данные
     * Обрезаем длину текста до 100 символов
     */
    public function printShortsArticles()
    {
        if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === 'user') {
            $valid = new Validation();
            $art = $this->articleModel->getAllArticles();
            foreach ($art as $val) {
                $article = new Publish($val);
                $this->view->article(
                    $article->getTitle(),
                    $valid->checkLengthArticle($article->getText()),
                    $article->getUser(),
                    $article->getDate()
                );
            }
        }
        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === 'admin') {
            $valid = new Validation();
            $art = $this->articleModel->getAllArticles();
            foreach ($art as $val) {
                $article = new Publish($val);
                $this->view->articleAdmin(
                    $article->getTitle(),
                    $valid->checkLengthArticle($article->getText()),
                    $article->getUser(),
                    $article->getDate()
                );
            }
        }
    }

    /**
     * Вывод полной статьи по нужному индексу
     */
    public function printAllArticles()
    {
        $art = $this->articleModel->getAllArticles();
        $article = new Publish($art[$_POST['index']]);
        $this->view->cardArticle(
            $article->getTitle(),
            $article->getText(),
            $article->getUser(),
            $article->getDate()
        );
    }

    /**
     * Заполнение блок случайными статьями
     */
    public function getRandomArticles()
    {
        $this->articleModel->setRandomArticles();
        $this->printShortsArticles();
    }

    /**
     * Удаление статьи
     */
    public function deleteArticle()
    {
        $arr = $this->helper->resetAPI();
        $this->articleModel->deleteArticle((int)$arr['indexDel']);
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
        $this->articleModel->edit($article);
    }

    /**
     * Добавление новой статьи в список
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
        if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === 'user') {
            $this->view->article(
                $userName['title'],
                $valid->checkLengthArticle($userName['text']),
                $userName['user'],
                $userName['date']
            );
        }
        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === 'admin') {
            $this->view->articleAdmin(
                $userName['title'],
                $valid->checkLengthArticle($userName['text']),
                $userName['user'],
                $userName['date']
            );
        }
    }
}