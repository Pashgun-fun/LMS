<?php
function createUsers()
{
    $data = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
    $dataUser = require_once __DIR__ . "/../../public/config/random_users.php";
    $mysql = mysqli_connect('192.168.10.10', $data['base']['username'], $data['base']['password'], $data['base']['database']);
    for ($j = 0; $j < 25; $j++) {
        $query = "INSERT INTO homestead.Users VALUES (null, 
                                                    '{$dataUser['login']}',
                                                    '{$dataUser['email']}',
                                                    '{$dataUser['pass']}',
                                                    '{$dataUser['data']}',
                                                    '{$dataUser['desc']}'
                                                    )";
        $mysql->query($query);
    }

}

createUsers();