<div class="edit__wrapper">
    <div class="edit__logo"></div>
    <form class="edit__form">
        <label class="add__email _field">
            Email<br>
            <input type="text" placeholder="Введите email" name="edit-email" class="edit-email"
                   value='<?=$email?>'><br>
            <span class=" _error error-email-edit"></span>
        </label>
        <label class="add__desc _field">
            Описание<br>
            <textarea placeholder="Краткое описание" name="edit-desc" class="edit-desc">'<?=$desc?>'</textarea>
        </label>
        <div class="edit__button">Изменить</div>
    </form>
</div>