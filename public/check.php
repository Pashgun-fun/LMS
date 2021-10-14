<?php
include "./forms.php";

$u = array('name' => check($_POST['login']), 'email' => check($_POST['email']), 'desc' => check($_POST['desc']), 'data' => check($_POST['data']), 'pass' => check($_POST['pass']));

$directory = "./database/";

$arr = array_values(myscandir($directory));

$newFile = $directory . count($arr) . '.txt';
$db = fopen($newFile, 'a+');
$str = json_encode($u);
$write = fwrite($db, $str);
echo user($u['name']);
?>