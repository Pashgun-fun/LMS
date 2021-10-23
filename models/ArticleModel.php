<?php

namespace models;

use core\Model;
use core\Helper;

class ArticleModel extends Model
{
    public string $directory;

    public Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/Articles/";
    }

    public function getAllArticles(): array
    {
        $arrayArticles = array();

        $arr = array_values($this->helper->myscandir($this->directory));
        foreach ($arr as $val) {
            $fileName = $this->directory . $val;
            $file = $this->readFile($fileName);
            array_push($arrayArticles, $file);
        }
        return $arrayArticles;
    }

    public function setRandomArticles()
    {
        $config = require_once __DIR__ . "/../public/config/random_articles_and_news.php";
        $arrFiles = $this->helper->myscandir($this->directory);
        asort($arrFiles);
        $lastFile = (+array_pop($arrFiles) + 1);
        $j = 0;
        while ($j <= 25) {
            $index = $lastFile + $j;
            $fileName = $this->directory . $index;
            $this->writeFile($fileName, $config['articles']);
            $j++;
        }
    }

    public function deleteArticle(int $indexDel)
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        asort($arr);
        $file = null;
        for ($j = 0; $j < count($arr); $j++) {
            if ($j === $indexDel) {
                $file = $arr[$j];
                break;
            }
        }
        unlink($this->directory . $file);
    }
}