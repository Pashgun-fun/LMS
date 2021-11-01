<?php
function createArticles()
{
    $data = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
    $dataArticles = require_once __DIR__ . "/../../public/config/random_articles_and_news.php";
    $mysql = mysqli_connect('192.168.10.10', $data['base']['username'], $data['base']['password'], $data['base']['database']);
    for ($j = 0; $j < 25; $j++) {
        $query = "INSERT INTO homestead.Articles VALUES (null, 
                                                    null ,
                                                    '{$dataArticles['articles']['title']}',
                                                    '{$dataArticles['articles']['text']}',
                                                    '{$dataArticles['articles']['date']}',
                                                    )";
        $mysql->query($query);
    }

}

createArticles();