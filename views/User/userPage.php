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
    .add__article, .add__news {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.2);
        z-index: 500;
    }

    .news {
        margin-bottom: 100px;
        display: grid;
        grid-template-columns: repeat(3, 4fr);
        grid-gap: 50px;
    }

    .article_pages, .news_pages {
        text-align: center;
        margin: 30px 0;
    }

    .article_page_nav:not(:last-child), .news_page_nav:not(:last-child) {
        margin-right: 1rem;
    }

    .active {
        font-weight: bold;
        font-size: 1.3rem;
        color: red;
    }

    .count_news, .count_articles {
        margin-left: 20px;
        opacity: 0.7;
    }
</style>

<header class="header">
    <div class="header__wrapper _container">
        <a>
            <div class="header__logo"></div>
        </a>
        <div class="header__buttons">
            <span><?= $name ?></span>
            <a class="header__exit">Exit</a>
        </div>
    </div>
</header>
<div class="app">
    <div class="users__wrapper _container">
        <?php
        foreach ($usersNameArr as $login) {
            if (is_array($login)) {
                echo('<div class="user">
                            <div class="user__wrapper">
                                <div class="user__name">' . $login['login'] . '</div>
                                <span class="id__user" style="opacity: 0">' . $login['id'] . '</span>
                            </div>
                        </div>');
            }
        }
        ?>
    </div>
</div>

<div class="_title _container _article">Статьи <span class="article__add">Добавить статью</span><span
            class="count_articles"></span></div>
<div class="article_pages _container"></div>
<section class="articles">
</section>

<div class="_title _container _news" style="margin-top: 100px">Новости <span
            class="news__add">Добавить новость</span><span class="count_news"></span>
</div>
<div class="news_pages _container"></div>
<section class="news _container">
</section>

<footer class="footer">
    <div class="footer__wrapper _container">
        Footer
    </div>
</footer>
<script type="text/javascript" src="/assets/js/jquery-3.6.0.min.js"></script>
<script type="module" src="/assets/js/main.js"></script>