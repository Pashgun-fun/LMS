var arrUsres = [];

$(() => {
    $('.add').hide();
    $('.edit').hide();
    deleteItem()
})

$('.users__addUser').on('click', () => {
    $('.add').show();
})

$('.add__close').on('click', () => {
    $('.add').hide();
})

function deleteItem() {
    document.querySelectorAll('.user').forEach(item => {
        item.addEventListener('click', e => {
            if (e.target.className == "user__del _button") {
                item.style.display = "none";
                item.classList.add("delete");
            }
        })
    })
}

deleteItem()

try {
    $(() => {
        $('.add__button').on('click', (e) => {
            var login = $('.login').val().trim();
            var email = $('.email').val().trim();
            var pass = $('.pass').val().trim();
            var confirm = $('.confirm').val().trim();
            var desc = $('.desc').val().trim();

            arrUsres.push({
                'name': login,
                'email': email,
                'desc': desc,
            });

            var err = 0;
            document.querySelectorAll('._check').forEach(item => {
                if (/^[a-zA-Z][a-zA-Z0-9-_\.]{4,20}$/.test(login) == false) {//с ограничением 2-20 символов, которыми могут быть буквы и цифры, первый символ обязательно буква
                    err++;
                    $('.error-login').html("Должен состоять не менее, чем из 4 символов и только из букви цифр и латиница");
                } else if (/[a-zA-Z]/.test(login[0]) == false) {
                    err++;
                    $('.error-login').html("Логин должен начинаться с буквы");
                } else {
                    $('.error-login').html('')
                }
                if (/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/.test(email) == false) {
                    err++;
                    $('.error-email').html('Введите email');
                } else {
                    $('.error-email').html('');
                }
                if (/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/.test(pass) == false) {//Строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов
                    err++;
                    $('.error-pass').html("Строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов");
                } else if (pass.length < 5) {
                    $('.error-pass').html("Слишком короткий пароль");
                } else {
                    $('.error-pass').html("");
                }
                if (pass != confirm) {
                    err++;
                    $('.error-pass').html("Пароли не совпадают");
                } else {
                    $('.error-pass').html("");
                }
            })

            if (err != 0) return

            $.ajax({
                url: './check.php',
                type: 'POST',
                cache: false,
                data: {
                    'login': login,
                    'email': email,
                    'pass': pass,
                    'desc': desc,
                    'conf': confirm,
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
                    deleteItem();
                    edit();
                },
            })
        })
    })
} catch (e) {
    console.error(e.name, e.message)
}

let edit = () => {
    $(() => {
        document.querySelectorAll('.user__edit').forEach((item, index) => {
            item.addEventListener('click', () => {
                $('.edit').show();
                $('.edit-login').val(arrUsres[index].name);
                $('.edit-email').val(arrUsres[index].email);
                $('.edit-desc').val(arrUsres[index].desc);
                $('.edit__button').on('click', (e) => {
                    e.preventDefault();
                    let login = $('.edit-login').val().trim();
                    let email = $('.edit-email').val().trim();
                    let pass = $('.edit-pass').val().trim();
                    let confirm = $('.edit-confirm').val().trim();
                    let desc = $('.edit-desc').val().trim();

                    if (login.length < 3) {
                        $('.error-login').text("Не менее 3 символов");
                        return false;
                    } else if (email.length < 3) {
                        $('.error-email').text("Введите email");
                        return false;
                    } else if (pass != confirm) {
                        $('.error-pass').text("Пароли должны совпадать");
                        return false;
                    }

                    $('._error').text("");

                    arrUsres[index].name = $('.edit-login').val();
                    arrUsres[index].email = $('.edit-email').val();
                    arrUsres[index].desc = $('.edit-desc').val();
                    $('.user__name').html($('.edit-login').val());
                    $('.edit').hide();
                })
            })
        })
    })
}