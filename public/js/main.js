let indexDel, indexEdit = 0;
let userArr = $('.user');
let del = document.querySelectorAll('.user__edit');

document.querySelectorAll('a').forEach(item => {
    item.addEventListener('click', e => {
        e.preventDefault();
    })
})

//Delete User
let deleteItem = () => {
    document.querySelectorAll('.user__del').forEach((item, index) => {
        item.onclick = e => {
            indexDel = index;
            $.ajax({
                url: './del.php',
                type: 'POST',
                cache: false,
                data: {
                    'indexDel': indexDel,
                },
                success: function () {
                    userArr[indexDel].remove();
                    $.ajax({
                        url: './sort.php',
                        type: 'POST',
                        success: function () {
                            return;
                        }
                    })
                },
                error: function () {
                    alert("Удаление прошло безуспешно");
                }

            })
        }
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

let checkDateTime = (newDate, el) => {
    if (/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(newDate) == false) {
        $(el).html("Введите корректную дату");
        return true;
    }
    let parseDate = newDate.split('.');
    for (let i = 0; i < parseDate.length; i++) {
        if (i == 1) {
            parseDate[i] = +parseDate[i] - 1;
        }
        parseDate[i] = +parseDate[i];
    }
    parseDate.reverse();
    let d = new Date(...parseDate);
    let today = new Date();
    if (today.getTime() - d.getTime() < 567648000000) {
        $(el).html("Вам меньше 18, попробуйте позже");
        return true;
    }
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
    editItem();
})

$('.users__addUser').on('click', () => {
    $('.add').show();
    history.pushState(null, null, '/new');
})

$('.add__close').on('click', () => {
    $('.add').hide();
    window.history.go(-1);
})

deleteItem();

//Check.php
$(() => {
    $('.add__button').on('click', (e) => {
        let [login, email, pass, confirm, desc, data] = [$('.login').val().trim(),
            $('.email').val().trim(),
            $('.pass').val().trim(),
            $('.confirm').val().trim(),
            $('.desc').val().trim(),
            $('.date').val().trim()];

        if (checkLogin(login, '.error-login')) return;

        if (checkEmail(email, '.error-email')) return;

        if (checkPass(pass, '.error-pass', confirm)) return;

        if (checkDateTime(data, '.error-dateTime')) return;

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
                    'data': data,
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
                    userArr = $('.user');
                    del = document.querySelectorAll('.user__edit');
                    editItem();
                    deleteItem();
                },
                error: function () {
                    console.log("Error");
                }
            })
        } catch (e) {
            console.error(e.name, e.message);
        }
    })
})

//OpenEdit.php
let editItem = () => {
    del.forEach((item, index) => {
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
                    'indexEdit': indexEdit,
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
                                userArr[index].querySelector('.user__name').innerHTML = login;
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
}

//Register
$('.header__register').on('click', e => {
    $('.add').show();
    history.pushState(null, null, '/registration');
})

//Login
let login = () => {
    $.ajax({
        url: "./login.php",
        method: "GET",
        success(response) {
            document.querySelector('.app').innerHTML = response;
        }
    })
}
$('.header__login').on('click', () => {
    login();
    history.pushState(null, null, '/login');
})

//Router
let locationResolver = (pathname) => {
    switch (pathname) {
        case "/new":
            $('.add').show();
            break;
        case "/registration":
            $('.add').show();
            break;
        case "/login":
            document.querySelector('.app').innerHTML = "";
            login();
            break;
    }
}

window.addEventListener('load', () => {
    let path = window.location.pathname;
    if (path) locationResolver(path);
})