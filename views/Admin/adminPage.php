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
            Продукты
        </a>

        <div class="header__buttons">
            <span><?= $name ?></span>
            <a class="header__exit">Exit</a>
        </div>
    </div>
</header>
<div class="product_search _container">
    <div class="products_body_pages"><label class="pages_for_products"></label>
        <input type="text" class="value_page_product">
        <span class="go_product_page">Перейти</span>
    </div>
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

<section class="error_search" style="color: red"></section>

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
        <section class="users">
            <div class="users__wrapper _container">
                <div class="users__add">
                    <div class="users__addTitle">Добавить нового пользователя</div>
                    <a class="users__addUser _button"></a>
                </div>
                <?php
                foreach ($usersNameArr as $login) {
                    if (is_array($login)) {
                        echo('<div class="user">
                            <div class="user__wrapper">
                                <div class="user__name">' . $login['login'] . '</div>
                                <span class="id__user" style="opacity: 0">' . $login['id'] . '</span>
                                <div class="user__buttons">
                                    <div class="user__edit _button"></div>
                                    <div class="user__del _button"></div>
                                </div>
                            </div>
                        </div>');
                    }
                }
                ?>
            </div>
        </section>
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
<div class="add">
    <div class="add__wrapper">
        <div class="add__container">
            <div class="add__title _title">Добавление нового пользователя</div>
            <form class="add__form">
                <label class="add__login _field">
                    Логин <br>
                    <input type="text" placeholder="Введите логин" name="login" class="login _check"><br>
                    <span class="_error error-login"></span>
                </label>
                <label class="add__email _field">
                    Email (Название)<br>
                    <input type="text" placeholder="Введите email" name="email" class="email _check"><br>
                    <span class="_error error-email"></span>
                </label>
                <label class="add__dataTime _field">
                    Введите дату рождени ДД.ММ.ГГГГ<br>
                    <input type="text" placeholder="Введите дату рождения" name="date" class="date _check"><br>
                    <span class="_error error-dateTime"></span>
                </label>
                <label class="add__password _field">
                    Пароль<br>
                    <input type="password" placeholder="Введите пароль" name="pass" class="pass _check"><br>
                    <span class="_error error-pass"></span>
                </label>
                <label class="add__passConf _field">
                    Подтверждение пароля<br>
                    <input type="password" placeholder="Подтвердите пароль" name="confirm" class="confirm _check">
                </label>
                <label class="add__desc _field">
                    Описание (Текст)<br>
                    <textarea placeholder="Краткое описание" name="desc" class="desc"></textarea>
                </label>
                <button type="button" class="add__button">Добавить</button>
                <div class="add__close">Закрыть</div>
            </form>
        </div>
    </div>
</div>

<div class="edit">
    <div class="edit__wrapper">
        <div class="edit__logo"></div>
        <form class="edit__form">
            <label class="edit__login _field">
                Логин <br>
                <input type="text" placeholder="Введите логин" name="edit-login" class="edit-login" value=''><br>
                <span class="_error error-login-edit"></span>
            </label>
            <label class="add__email _field">
                Email (Название)<br>
                <input type="text" placeholder="Введите email" name="edit-email" class="edit-email" value=''><br>
                <span class=" _error error-email-edit"></span>
            </label>
            <label class="add__password _field">
                Пароль<br>
                <input type="password" placeholder="Введите пароль" name="pass" class="edit-pass"><br>
                <span class="_error error-pass-edit"></span>
            </label>
            <label class="add__passConf _field">
                Подтверждение пароля<br>
                <input type="password" placeholder="Подтвердите пароль" name="confirm" class="edit-confirm">
            </label>
            <label class="add__desc _field">
                Описание (Текст)<br>
                <textarea placeholder="Краткое описание" name="edit-desc" class="edit-desc"></textarea>
            </label>
            <div class="edit__button">Изменить</div>
        </form>
    </div>
</div>

<div class="add__article">
    <div class="add__wrapper">
        <div class="add__container">
            <div class="add__title _title">Добавление новой статьи</div>
            <form class="add__form">
                <label class="add__email _field">
                    Название<br>
                    <input type="text" placeholder="Введите email" name="email" class="publish_title _check"><br>
                    <span class="_error error-email"></span>
                </label>
                <label class="add__desc _field">
                    Текст<br>
                    <textarea placeholder="Краткое описание" name="desc" class="publish_text"></textarea>
                </label>
                <button type="button" class="add__button__article">Добавить</button>
                <div class="add__close">Закрыть</div>
            </form>
        </div>
    </div>
</div>

<div class="add__news">
    <div class="add__wrapper">
        <div class="add__container">
            <div class="add__title _title">Добавление новости</div>
            <form class="add__form">
                <label class="add__email _field">
                    Название<br>
                    <input type="text" placeholder="Введите email" name="email" class="news_title _check"><br>
                    <span class="_error error-email"></span>
                </label>
                <label class="add__desc _field">
                    Текст<br>
                    <textarea placeholder="Краткое описание" name="desc" class="news_text"></textarea>
                </label>
                <button type="button" class="add__button__news">Добавить</button>
                <div class="add__close">Закрыть</div>
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