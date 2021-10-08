var indexDel = 0;

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
    document.querySelectorAll('.user').forEach((item,index) => {
        item.addEventListener('click', e => {
            if (e.target.classList.contains('user__del')) {
                indexDel = index;
                $.ajax({
                    url:'./del.php',
                    type: 'POST',
                    cache: false,
                    data: {
                        'indexDel':indexDel,
                    },
                    success(){
                      location.reload();
                    }
                })
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
            })
        })
    })
} catch (e) {
    console.error(e.name, e.message)
}