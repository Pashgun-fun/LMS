<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>

<header class="header">
    <div class="header__wrapper _container">
        <a href="./index.php">
            <div class="header__logo"></div>
        </a>
        <div class="header__buttons">
            <a class="header__login">Login</a>
            <a class="header__register">Register</a>
        </div>
    </div>
</header>

<section class="users">
    <div class="users__wrapper _container">
        <div class="users__add">
            <div class="users__addTitle">Добавить нового пользователя</div>
            <div class="users__addUser _button"></div>
        </div>
        <?php
        $_SESSION['length'] = null;
        $file = 'db.txt';
        if (filesize($file) != 0) {
            $db = fopen($file, 'a+');
            $read = trim(fread($db, filesize($file)),"\n");
            $arr = explode("\n", $read);
            for ($i = 0; $i < count($arr); $i++) {
                $el = json_decode($arr[$i], true);
                echo('<div class="user">
            <div class="user__wrapper">
                <div class="user__name">' . $el['name'] . '</div>
                <div class="user__buttons">
                    <div class="user__edit _button"></div>
                    <div class="user__del _button"></div>
                </div>
            </div>
        </div>');
            }
            $_SESSION['length'] = $arr;
        }

        ?>
    </div>
</section>

<footer class="footer">
    <div class="footer__wrapper _container">
        Footer
    </div>
</footer>

<div class="add">
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

<div class="edit">
    <div class="edit__wrapper">
        <div class="edit__logo"></div>
        <form class="edit__form">
            <label class="edit__login _field">
                Логин <br>
                <input type="text" placeholder="Введите логин" name="login" class="edit-login"><br>
                <span class="_error error-login-edit"></span>
            </label>
            <label class="add__email _field">
                Email<br>
                <input type="text" placeholder="Введите email" name="email" class="edit-email"><br>
                <span class="_error error-email-edit"></span>
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
                Описание<br>
                <textarea placeholder="Краткое описание" name="desc" class="edit-desc"></textarea>
            </label>
            <button type="button" class="edit__button">Изменить</button>
        </form>
    </div>
</div>


<script type="text/javascript" src="./js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>