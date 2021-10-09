<?php
require "./forms.php";

$file = 'db.txt';

try {
    $u = array('name' => check($_POST['login']), 'email' => check($_POST['email']), 'desc' => check($_POST['email']));
    $db = fopen($file, 'a+');
    $str = json_encode($u) . "\n";
    $write = fwrite($db, $str);
    $read = fread($db, filesize($file));
    $el = json_decode($read, true);
    echo(user($el['name']));
} catch (Exception $e) {
    echo($e->getMessage());
}

?>