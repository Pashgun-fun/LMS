<div class="edit__wrapper">
    <div class="edit__logo"></div>
    <form class="edit__form">
        <label class="edit__login _field">
            Логин <br>
            <input type="text" placeholder="Введите логин" name="edit-login" class="edit-login" value='<?=$name?> '><br>
            <span class="_error error-login-edit"></span>
        </label>
        <label class="add__email _field">
            Email<br>
            <input type="text" placeholder="Введите email" name="edit-email" class="edit-email"
                   value='<?=$email?>'><br>
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
            <textarea placeholder="Краткое описание" name="edit-desc" class="edit-desc">'<?=$desc?>'</textarea>
        </label>
        <div class="edit__button">Изменить</div>
    </form>
</div>