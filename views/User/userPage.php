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

    .product_menu {
        font-size: 14px;
        display: none;
        grid-template-columns: repeat(9, 1fr);
        text-align: center;
        text-transform: uppercase;
        font-weight: bold;
        margin-top: 50px;
        margin-bottom: 30px;
        align-items: center;
    }

    .product_search {
        display: none;
        margin-bottom: 10px;
    }

    .product_search div {
        margin-bottom: 10px;
    }

    .product_search span {
        display: inline-block;
        padding: 0 20px;
        height: 40px;
        text-align: center;
        background: red;
        vertical-align: middle;
        line-height: 40px;
        font-weight: bold;
    }

    .product_filterByChapter {
        width: 20px;
        height: 15px;
        display: inline-block;
        vertical-align: middle;
    }

    .product_filterByChapter img {
        object-fit: cover;
        width: 100%;
    }
</style>

<header class="header">
    <div class="header__wrapper _container">
        <a>
            <div class="header__logo"></div>
        </a>

        <a class="products">
            Продукты
        </a>

        <div class="header__buttons">
            <span><?= $name ?></span>
            <a class="header__exit">Exit</a>
        </div>
    </div>
</header>
<div class="product_search _container">
    <div>
        <label>Поиск по разделу <input type="text" class="search_product"></label> <span class="product_search_button">Найти</span><br>
    </div>
    <div>
        <label>Поиск по подразделу <input type="text" class="search_product_subchapter"></label> <span
                class="product_search_subchapter">Найти</span><br>
    </div>
    <div>
        <label>Поиск по бренду <input type="text" class="search_product_brend"></label> <span
                class="product_search_brend">Найти</span><br>
    </div>
    <div>
        <label>Поиск по модели <input type="text" class="search_product_model"></label> <span
                class="product_search_model">Найти</span><br>
    </div>
    <div>
        <label>Поиск по цвету <input type="text" class="search_product_color"></label> <span
                class="product_search_color">Найти</span><br>
        <span class="product_search_allCategories">Найти всё</span>
        <span class="product_search_all">Вывести всё</span>
    </div>
</div>

<div class="product_menu _container">
    <div class="product_menu_chapter">Раздел <span class="product_filterByChapter"><img
                    src="assets/img/ascending-sort.png" alt=""></span></div>
    <div class="product_menu_subchapter">Подраздел</div>
    <div class="product_menu_articul">Артикул</div>
    <div class="product_menu_brend">Бренд</div>
    <div class="product_menu_model">Модель</div>
    <div class="product_menu_namespace">Наименование товара</div>
    <div class="product_menu_size">Размер</div>
    <div class="product_menu_color">Цвет</div>
    <div class="product_menu_orientation">Ориентация (для клюшки)</div>
</div>

<section class="products_body _container">
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
</section>

<footer class="footer">
    <div class="footer__wrapper _container">
        Footer
    </div>
</footer>
<script type="text/javascript" src="/assets/js/jquery-3.6.0.min.js"></script>
<script type="module" src="/assets/js/main.js"></script>