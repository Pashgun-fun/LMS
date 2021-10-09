let indexDel, indexEdit = 0;

let deleteItem = () => {
    document.querySelectorAll('.user__del').forEach((item, index) => {
        item.addEventListener('click', e => {
            e.preventDefault();
            indexDel = index;
            $.ajax({
                url: './del.php',
                type: 'POST',
                cache: false,
                data: {
                    'indexDel': indexDel,
                },
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert("Удаление прошло безуспешно");
                }

            })
        })
    })
}

//Validation
let checkLogin = (login, el) => {
    if (/^[a-zA-Z][a-zA-Z0-9-_\.]{4,20}$/.test(login) == false) {//с ограничением 2-20 символов, которыми могут быть буквы и цифры, первый символ обязательно буква
        $(el).html("Должен состоять не менее, чем из 4 символов и только из букви цифр и латиница");
        return true;
    }
    if (/[a-zA-Z]/.test(login[0]) == false) {
        $(el).html("Логин должен начинаться с буквы");
        return true;
    }
    $(el).html("");
}

let checkEmail = (email, el) => {
    if (/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/.test(email) == false) {
        $(el).html('Введите email');
        return true;
    }
    $(el).html("");
}

let checkPass = (pass, el, confirm) => {
    if (pass.length < 8) {//Строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов
        $(el).html("Слишком короткий пароль");
        return true;
    }
    if (pass !== confirm) {
        $(el).html("Пароли не совпадают");
        return true;
    }
    if (/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/.test(pass) == false) {
        $(el).html("Строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов");
        return true;
    }
    $(el).html("");
}

//Hide form of edit user
let invisible = () => {
    if ($('.edit').hasClass('show')) {
        document.addEventListener('click', e => {
            if (!e.target.closest('.user__edit') && !e.target.closest('.edit')) {
                $('.edit').hide();
            }
            return;
        })
    }
}

//Hide and show
$(() => {
    deleteItem();
})

$('.users__addUser').on('click', () => {
    $('.add').show();
})

$('.add__close').on('click', () => {
    $('.add').hide();
})

deleteItem();

//Check.php
$(() => {
    $('.add__button').on('click', (e) => {
        let [login, email, pass, confirm, desc] = [$('.login').val().trim(),
            $('.email').val().trim(),
            $('.pass').val().trim(),
            $('.confirm').val().trim(),
            $('.desc').val().trim()];

        if (checkLogin(login, '.error-login')) return;

        if (checkEmail(email, '.error-email')) return;

        if (checkPass(pass, '.error-pass', confirm)) return;

        try {
            $.ajax({
                url: './check.php',
                type: 'POST',
                cache: false,
                data: {
                    'login': login,
                    'email': email,
                    'pass': pass,
                    'desc': desc,
                },
                dataType: 'html',
                beforeSend: function () {
                    $('.add__button').prop("disabled", true);
                },
                success: function (data) {
                    $('.users__wrapper').append(data);
                    $('.add__form').trigger("reset");
                    $('.add').hide();
                    $('.add__button').prop("disabled", false);
                    location.reload();
                    deleteItem();
                },
                error: function (dataErr) {
                    alert(dataErr);
                }
            })
        } catch (e) {
            console.error(e.name, e.message);
        }
    })
})

//OpenEdit.php
$(() => {
    document.querySelectorAll('.user__edit').forEach((item, index) => {
        item.addEventListener('click', () => {
            $('.edit').show();
            $('.edit').addClass('show');

            invisible();

            indexEdit = index;
            $.ajax({
                url: './OpenEdit.php',
                type: 'POST',
                cache: false,
                dataType: 'html',
                data: {
                    'indexDel': indexEdit,
                },
                success(response) {
                    $('.edit').html(response);

                    $('.edit__button').on('click', e => {
                        e.preventDefault();

                        let [login, email, pass, confirm, desc] = [$('.edit-login').val().trim(),
                            $('.edit-email').val().trim(),
                            $('.edit-pass').val().trim(),
                            $('.edit-confirm').val().trim(),
                            $('.edit-desc').val().trim()
                        ];

                        if (checkLogin(login, '.error-login-edit')) return;

                        if (checkEmail(email, '.error-email-edit')) return;

                        if (checkPass(pass, '.error-pass-edit', confirm)) return;


                        $.ajax({
                            url: './edit.php',
                            type: 'POST',
                            cache: false,
                            data: {
                                'index': indexEdit,
                                'login': login,
                                'email': email,
                                'desc': desc,
                            },
                            dataType: 'html',
                            beforeSend: function () {
                                $('.edit__button').prop("disabled", true);
                            },
                            success: function () {
                                $('.edit__button').prop("disabled", false);
                                location.reload();
                            }
                        })
                        $('.edit').hide();
                    })
                },
                error: function () {
                    alert("Редактирование не возможно");
                }
            })
        })
    })
})