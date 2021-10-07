<?php
$login = $_POST['login'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$desc = $_POST['desc'];
$file = 'db.txt';

$u = array('name' => $login, 'email' => $email, 'desc' => $desc);


try {
    $db = fopen($file, 'a+');
    $str = serialize($u);
    $write = fwrite($db, $str);
    $read = fread($db, filesize($file));
    $user = unserialize($read);
    echo('<div class="user">
            <div class="user__wrapper">
                <div class="user__name">' . $user['name'] . '</div>
                <div class="user__buttons">
                    <div class="user__edit _button"></div>
                    <div class="user__del _button"></div>
                </div>
            </div>
        </div>');
} catch (Exception $e) {
    echo($e->getMessage());
}
fclose($db);
?>
