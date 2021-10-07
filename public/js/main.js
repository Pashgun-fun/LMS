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
            let login = $('.login').val().trim();
            let email = $('.email').val().trim();
            let pass = $('.pass').val().trim();
            let confirm = $('.confirm').val().trim();
            let desc = $('.desc').val().trim();

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

            arrUsres.push({
                'name': login,
                'email': email,
                'desc': desc,
            });

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
                    // e.preventDefault();
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

                    // $.ajax({
                    //     url: './check.php',
                    //     cache: false,
                    //     type: 'POST',
                    //     data: {
                    //         'index': index,
                    //         'login-change': login,
                    //         'email-change': email,
                    //         'desc-change': desc,
                    //     },
                    //     dataType: 'html',
                    //     success: function () {
                    //
                    //         $('.edit').hide();
                    //     }
                    // })


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