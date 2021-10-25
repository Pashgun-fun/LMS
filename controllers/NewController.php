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
     * Удаление новости
     */
    public function deleteNews()
    {
        $arr = $this->helper->resetAPI();
        $this->newModel->deleteNews((int)$arr['time']);
    }

}