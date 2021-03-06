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

    .products_body_pages span {
        background: initial;
    }

    .products_body {
        margin-bottom: 70px;
    }
</style>

<body class="body">
<header class="header">
    <div class="header__wrapper _container">
        <a>
            <div class="header__logo"></div>
        </a>

        <a class="products">
            ????????????????
            <div class="count_products"></div>
        </a>

        <div class="header__buttons">
            <a class="header__login">Login</a>
            <a class="header__register">Register</a>
        </div>
    </div>
</header>

<div class="product_search _container">
    <div class="products_body_pages"><label class="pages_for_products"></label>
        <input type="text" class="value_page_product">
        <span class="go_product_page">??????????????</span>
    </div>
    <div>
        <label>?????????? ???? ?????????????? <input type="text" class="search_product"></label> <span class="product_search_button">??????????</span><br>
    </div>
    <div>
        <label>?????????? ???? ???????????????????? <input type="text" class="search_product_subchapter"></label> <span
                class="product_search_subchapter">??????????</span><br>
    </div>
    <div>
        <label>?????????? ???? ???????????? <input type="text" class="search_product_brend"></label> <span
                class="product_search_brend">??????????</span><br>
    </div>
    <div>
        <label>?????????? ???? ???????????? <input type="text" class="search_product_model"></label> <span
                class="product_search_model">??????????</span><br>
    </div>
    <div>
        <label>?????????? ???? ?????????????? <input type="text" class="search_product_size"></label> <span
                class="product_search_size">??????????</span><br>
    </div>

    <div>
        <label>?????????? ???? ?????????? <input type="text" class="search_product_color"></label> <span
                class="product_search_color">??????????</span><br>
        <span class="product_search_allCategories">?????????? ??????</span>
        <span class="product_search_all">?????????????? ??????</span>
    </div>
</div>

<section class="error_search" style="color: red"></section>
<div class="product_menu _container">
    <div class="product_menu_chapter">???????????? <span class="product_filterByChapter"><img
                    src="assets/img/ascending-sort.png" alt=""></span></div>
    <div class="product_menu_subchapter">??????????????????</div>
    <div class="product_menu_articul">??????????????</div>
    <div class="product_menu_brend">??????????</div>
    <div class="product_menu_model">????????????</div>
    <div class="product_menu_namespace">???????????????????????? ????????????</div>
    <div class="product_menu_size">????????????</div>
    <div class="product_menu_color">????????</div>
    <div class="product_menu_orientation">???????????????????? (?????? ????????????)</div>
</div>

<section class="products_body _container">
    <div class="_title _container _article">????????????<span class="count_articles"></span></div>
    <div class="article_pages _container"></div>
    <section class="articles">
    </section>

    <div class="_title _container _news" style="margin-top: 100px">?????????????? <span class="count_news"></span></div>
    <div class="news_pages _container"></div>
    <section class="news _container">
    </section>
</section>

<div class="add">
    <div class="add__wrapper">
        <div class="add__container">
            <div class="add__title _title">???????????????????? ???????????? ????????????????????????</div>
            <form class="add__form">
                <label class="add__login _field">
                    ?????????? <br>
                    <input type="text" placeholder="?????????????? ??????????" name="login" class="login _check"><br>
                    <span class="_error error-login"></span>
                </label>
                <label class="add__email _field">
                    Email<br>
                    <input type="text" placeholder="?????????????? email" name="email" class="email _check"><br>
                    <span class="_error error-email"></span>
                </label>
                <label class="add__dataTime _field">
                    ?????????????? ???????? ?????????????? ????.????.????????<br>
                    <input type="text" placeholder="?????????????? ???????? ????????????????" name="date" class="date _check"><br>
                    <span class="_error error-dateTime"></span>
                </label>
                <label class="add__password _field">
                    ????????????<br>
                    <input type="password" placeholder="?????????????? ????????????" name="pass" class="pass _check"><br>
                    <span class="_error error-pass"></span>
                </label>
                <label class="add__passConf _field">
                    ?????????????????????????? ????????????<br>
                    <input type="password" placeholder="?????????????????????? ????????????" name="confirm" class="confirm _check">
                </label>
                <label class="add__desc _field">
                    ????????????????<br>
                    <textarea placeholder="?????????????? ????????????????" name="desc" class="desc"></textarea>
                </label>
                <button type="button" class="add__button">????????????????</button>
                <div class="add__close">??????????????</div>
            </form>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="footer__wrapper _container">
        Footer
    </div>
</footer>

<script type="text/javascript" src="/assets/js/jquery-3.6.0.min.js"></script>
<script type="module" src="/assets/js/main.js"></script>