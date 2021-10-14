<?php
function user($name)
{
    return ('<div class="user">
            <div class="user__wrapper">
                <div class="user__name">' . $name . '</div>
                <div class="user__buttons">
                    <div class="user__edit _button"></div>
                    <div class="user__del _button"></div>
                </div>
            </div>
        </div>');
}

function edit($name, $email, $desc)
{
    return ('<div class="edit__wrapper">
                                        <div class="edit__logo"></div>
                                        <form class="edit__form">
                                            <label class="edit__login _field">
                                                Логин <br>
                                                <input type="text" placeholder="Введите логин" name="edit-login" class="edit-login" value=' . $name . '><br>
                                                <span class="_error error-login-edit"></span>
                                            </label>
                                            <label class="add__email _field">
                                                Email<br>
                                                <input type="text" placeholder="Введите email" name="edit-email" class="edit-email" value=' . $email . '><br>
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
                                                Описание<br>
                                                <textarea placeholder="Краткое описание" name="edit-desc" class="edit-desc">' . $desc . '</textarea>
                                            </label>
                                            <div class="edit__button">Изменить</div>
                                        </form>
                                   </div>');
}

function login()
{
    return ('<div class="login">
                <div class="login__wrapper _container">
                    <div class="login__title">Авторизация</div>
                    <label class="add__email _field">
                        Email<br>
                        <input type="text" placeholder="Введите email" name="edit-email" class="edit-email"><br>
                        <span class=" _error error-email-edit"></span>
                    </label>
                    <label class="add__password _field">
                        Пароль<br>
                        <input type="password" placeholder="Введите пароль" name="pass" class="edit-pass"><br>
                        <span class="_error error-pass-edit"></span>
                    </label>
                    <div class="edit__button _enter">Авторизоваться</div>
                </div>
             </div>');
}

function check($value)
{
    return stripslashes(strip_tags(htmlspecialchars(trim($value))));
}

//function myscandir($dir, $sort = 0)
//{
//    $list = scandir($dir, $sort);
//
//    if (!$list) return false;
//
//    if ($sort == 0) unset($list[0], $list[1]);
//    else unset($list[count($list) - 1], $list[count($list) - 1]);
//    return $list;
//}

?>