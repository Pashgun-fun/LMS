<?php
$login = $_POST['login'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$desc = $_POST['desc'];

$user = '<div class="user">
            <div class="user__wrapper">
                <div class="user__name" contenteditable="true">'.$login.'</div>
                <div class="user__buttons">
                    <div class="user__edit _button"></div>
                    <div class="user__del _button"></div>
                </div>
            </div>
        </div>';
echo($user);
?>
