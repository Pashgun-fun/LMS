<?php

namespace controllers;

use core\Controller;
use core\Helper;
use models\Article;
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

    public function printShortsArticles()
    {
        if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === 'user') {
            $valid = new Validation();
            $art = $this->articleModel->getAllArticles();
            foreach ($art as $val) {
                $article = new Article($val);
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
                $article = new Article($val);
                $this->view->articleAdmin(
                    $article->getTitle(),
                    $valid->checkLengthArticle($article->getText()),
                    $article->getUser(),
                    $article->getDate()
                );
            }
        }
    }

    public function printAllArticles()
    {
        $art = $this->articleModel->getAllArticles();
        $article = new Article($art[$_POST['index']]);
        $this->view->cardArticle(
            $article->getTitle(),
            $article->getText(),
            $article->getUser(),
            $article->getDate()
        );
    }

    public function getRandomArticles()
    {
        $this->articleModel->setRandomArticles();
        $this->printShortsArticles();
    }

    public function deleteArticle()
    {
        $arr = $this->helper->resetAPI();
        $this->articleModel->deleteArticle(+$arr['indexDel']);
    }


}