<?php

namespace models;

use core\Model;
use core\Helper;
use entites\Publish;

class NewModel extends Model
{
    public string $directory;
    public string $config;

    public Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/News/";
        $this->config = __DIR__ . "/../public/config/random_articles_and_news.php";
    }

    /**
     * Добавляем необходимое количество статей для заполнения сайта
     */
    public function getAllNews(): array
    {
        return $this->publishing($this->directory);
    }

    /**
     * Добавляем необходимое количество статей для заполнения сайта
     */
    public function setRandomNews()
    {
        $this->publishRandom($this->directory, $this->config);
    }

    /**
     * Удаление новости, по истечении суток, из ленты
     * Чтобы понять, можно ли удалять файл из базы, мы сравниваем занесенное туда время с текущим
     * Время заносится, когда новость создалась в формате количества секунд с 1970 года
     */
    public function deleteNews(int $time)
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        foreach ($arr as $val) {
            $fileName = $this->directory . $val;
            $infoAboutNew = $this->readFile($fileName);
            $differentTime = +$time - $infoAboutNew['seconds'];
            var_dump($differentTime);
            if ($differentTime >= 86400) {
                unlink($this->directory . $val);
            }
        }
    }

    /**
     * Ручное удаление новости
     */
    public function removeNews(int $indexDel)
    {
        var_dump($this->directory);
        $this->delete($this->directory, $indexDel);
    }

    public function openEditWindowNews(int $indexEdit): array
    {
        return $this->openEdit($this->directory, $indexEdit);
    }

    public function edit(Publish $publish)
    {
        $this->editForArticlesAndNews($publish, $this->directory);
    }
}