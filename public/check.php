<?php
$login = $_POST['login'];
$email = $_POST['email'];
$desc = $_POST['desc'];
$file = 'db.txt';
try {
    $u = array('name' => $login, 'email' => $email, 'desc' => $desc);
    $db = fopen($file, 'a+');
    $str = json_encode($u). "\n";
    $write = fwrite($db, $str);
    $read = fread($db, filesize($file));
    $user = json_decode($read, true);
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
?>