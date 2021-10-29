<?php
function createNews()
{
    $data = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
    $dataArticles = require_once __DIR__ . "/../../public/config/random_articles_and_news.php";
    $mysql = mysqli_connect('192.168.10.10', $data['base']['username'], $data['base']['password'], $data['base']['database']);
    for ($j = 0; $j < 25; $j++) {
        $query = "INSERT INTO homestead.News VALUES (null, 
                                                    null ,
                                                    '{$dataArticles['news']['title']}',
                                                    '{$dataArticles['news']['text']}',
                                                    '{$dataArticles['news']['date']}',
                                                    {$dataArticles['news']['seconds']}
                                                    )";
        $mysql->query($query);
    }

}

createNews();