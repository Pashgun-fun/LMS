<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Document</title>
</head>
<header class="header">
    <div class="header__wrapper _container">
        <a>
            <div class="header__logo"></div>
        </a>
        <div class="header__buttons">
            <a class="header__exit">Exit</a>
        </div>
    </div>
</header>
<div class="app">
    <div class="users__wrapper _container">
        <?php
        foreach ($usersNameArr as $login){
            echo('<div class="user">
                            <div class="user__wrapper">
                                <div class="user__name">' . $login . '</div>
                            </div>
                        </div>');
        }
        ?>
    </div>
</div>
<footer class="footer">
    <div class="footer__wrapper _container">
        Footer
    </div>
</footer>
<script type="text/javascript" src="/assets/js/jquery-3.6.0.min.js"></script>
<script type="module" src="/assets/js/main.js"></script>