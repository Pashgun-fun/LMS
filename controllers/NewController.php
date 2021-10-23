<?php

namespace controllers;

use core\Controller;
use core\Validation;
use core\Helper;
use models\NewModel;
use models\News;

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

    public function printShortsNews()
    {
        $valid = new Validation();
        $art = $this->newModel->getAllNews();
        foreach ($art as $val) {
            $article = new News($val);
            $this->view->news(
                $article->getTitle(),
                $valid->checkLengthArticle($article->getText()),
                $article->getUser(),
                $article->getDate()
            );
        }
    }

    public function printAllArticles()
    {
        $art = $this->newModel->getAllNews();
        $article = new News($art[$_POST['index']]);
        $this->view->cardArticle(
            $article->getTitle(),
            $article->getText(),
            $article->getUser(),
            $article->getDate()
        );
    }

    public function getRandomNews()
    {
        $this->newModel->setRandomNews();
        $this->printShortsNews();
    }

}