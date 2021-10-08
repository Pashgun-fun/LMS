<?php
session_start();
$file = 'db.txt';
$index = $_POST['indexDel'];
$db = fopen($file, 'a+');
$read = trim(fread($db, filesize($file)), "\n\r");
$arr = explode("\n", $read);
$el = json_decode($arr[$index], true);
echo(json_encode($el));
?>