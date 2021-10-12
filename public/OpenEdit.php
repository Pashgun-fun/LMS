<?php
session_start();

require "./forms.php";

$directory = './dataBase/';
$db = fopen($directory.$_POST['indexEdit'].'.txt', 'a+');
$read = trim(fread($db, filesize($directory.$_POST['indexEdit'].'.txt')));
$el = json_decode($read, true);

echo(edit($el['name'], $el['email'], $el['desc']));

?>