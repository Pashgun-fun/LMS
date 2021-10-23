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

<body class="body">
<header class="header">
    <div class="header__wrapper _container">
        <a>
            <div class="header__logo"></div>
        </a>
        <div class="header__buttons">
            <a class="header__login">Login</a>
            <a class="header__register">Register</a>
        </div>
    </div>
</header>

<div class="_title _container">Статьи</div>
<section class="articles">
</section>

<div class="generator-article _container">Сгенерировать статьи</div>

<div class="_title _container"  style="margin-top: 100px">Новости</div>
<section class="news">
</section>

<div class="generator-news _container" style="margin-bottom: 100px">Сгенерировать новости</div>


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
                    Email<br>
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
                    Описание<br>
                    <textarea placeholder="Краткое описание" name="desc" class="desc"></textarea>
                </label>
                <button type="button" class="add__button">Добавить</button>
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