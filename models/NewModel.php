<?php

namespace models;

use core\Model;
use core\Helper;

class NewModel extends Model
{
    public string $directory;

    public Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/News/";
    }

    public function getAllNews(): array
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

    public function setRandomNews(){
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
}