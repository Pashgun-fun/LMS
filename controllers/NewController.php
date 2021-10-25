<?php

namespace controllers;

use core\Controller;
use core\Validation;
use core\Helper;
use models\NewModel;
use entites\Publish;

class NewController extends Controller
{
    protected Helper $helper;
    protected NewModel $newModel;

    function __construct()
    {
        parent::__construct();
        $this->newModel = new NewModel();
        $this->helper = new Helper();
    }

    /**
     * Вывод новостей с валидацией
     * Создаётся сущность и через геттеры выводим данные
     * Обрезаем длину текста до 100 символов
     */
    public function printShortsNews()
    {
        if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === 'user') {
            $valid = new Validation();
            $art = $this->newModel->getAllNews();
            foreach ($art as $val) {
                $article = new Publish($val);
                $this->view->news(
                    $article->getTitle(),
                    $valid->checkLengthArticle($article->getText()),
                    $article->getUser(),
                    $article->getDate()
                );
            }
        }
        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === 'admin') {
            $valid = new Validation();
            $art = $this->newModel->getAllNews();
            foreach ($art as $val) {
                $article = new Publish($val);
                $this->view->newAdmin(
                    $article->getTitle(),
                    $valid->checkLengthArticle($article->getText()),
                    $article->getUser(),
                    $article->getDate()
                );
            }
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
     * Заполнение блок случайными новостями
     */
    public function getRandomNews()
    {
        $this->newModel->setRandomNews();
        $this->printShortsNews();
    }

    /**
     * Удаление новости по истечении суток
     */
    public function deleteNews()
    {
        $arr = $this->helper->resetAPI();
        $this->newModel->deleteNews((int)$arr['time']);
    }

    /**
     * Ручное удаление новости
     */
    public function removeNews()
    {
        $arr = $this->helper->resetAPI();
        $this->newModel->removeNews((int)$arr['indexDel']);
    }

    public function windowEdit()
    {
        $this->editNews();
    }

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
}