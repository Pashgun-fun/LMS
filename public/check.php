<?php
require "./forms.php";

$u = array('name' => check($_POST['login']), 'email' => check($_POST['email']), 'desc' => check($_POST['desc']), 'data' => check($_POST['data']));

$directory = "./database/";

$arrayFiles = array_values(myscandir($directory));
$newFile = $directory.count($arrayFiles).'.txt';
$db = fopen($newFile, 'a+');
$str = json_encode($u);
$write = fwrite($db, $str);
echo user($u['name']);
?>