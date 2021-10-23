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

<style>
    .generator-article {
        color: black;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 1.1rem;
        text-align: center;
        height: 50px;
        line-height: 50px;
        width: 30%;
        background: red;
        margin-top: 50px;
        margin-bottom: 100px;
    }
</style>

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
        foreach ($usersNameArr as $login) {
            echo('<div class="user">
                            <div class="user__wrapper">
                                <div class="user__name">' . $login . '</div>
                            </div>
                        </div>');
        }
        ?>
    </div>
</div>

<div class="_title _container">Статьи</div>
<section class="articles">
</section>

<div class="generator-article _container">Сгенерировать статьи</div>


<div class="_title _container"  style="margin-top: 100px">Новости</div>
<section class="news">
</section>

<div class="generator-news _container" style="margin-bottom: 100px">Сгенерировать новости</div>

<footer class="footer">
    <div class="footer__wrapper _container">
        Footer
    </div>
</footer>
<script type="text/javascript" src="/assets/js/jquery-3.6.0.min.js"></script>
<script type="module" src="/assets/js/main.js"></script>