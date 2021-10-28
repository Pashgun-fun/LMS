<?php

function myscandir(string $dir, int $sort = 0)
{
    $list = scandir($dir, $sort);

    if (!$list) {
        return $list;
    }

    if ($sort === 0) {
        unset($list[0], $list[1]);
    }
    return $list;
}

function writeFile(string $newFile, array $userData)
{
    $db = fopen($newFile, 'a+');
    $str = json_encode($userData);
    fwrite($db, $str);
}

/**
 * Добавляем необходимое количество статей для заполнения сайта
 */
function publishRandom()
{
    /**
     * Данные для скелетона статьи берутся из config`a
     */
    $config = require_once __DIR__ . "/../public/config/random_articles_and_news.php";
    /**
     * Далее сканируется дирректория, где будут размещаться новые статьи
     * Сортируется, чтобы наибольший индекс файла был послденим в списке
     * Получаем значение последнего элемента, который и будет являться максимальным индексом
     * Создаем новый индекс для новго файла на еденицу больше предыдущего
     */
    $arrFiles = myscandir(__DIR__ . "/../database/Articles/");
    asort($arrFiles);
    $lastFile = ((int)array_pop($arrFiles) + 1);
    $j = 0;
    /**
     * СОздаём новые файлы в том колибесвте, сколшько нам необходимо
     */
    while ($j <= 24) {
        $index = $lastFile + $j;
        $fileName = __DIR__ . "/../database/Articles/" . $index;
        writeFile($fileName, $config['articles']);
        $j++;
    }
}

publishRandom();