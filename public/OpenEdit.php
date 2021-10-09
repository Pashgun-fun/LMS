<?php
session_start();

require "./forms.php";

$file = 'db.txt';
$db = fopen($file, 'a+');
$read = trim(fread($db, filesize($file)), "\n\r");
$arr = explode("\n", $read);
$el = json_decode($arr[$_POST['indexDel']], true);


print_r($el['desc']);
echo(edit($el['name'], $el['email'], $el['desc']));



?>