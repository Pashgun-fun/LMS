import {articles, deleteUser, newUser, openWindowEdit, getMaket, login, enterUser, exitUser} from "./ajax.js";
import {checkLogin, checkEmail, checkPass, checkDateTime} from "./validation.js";

//Hide and show
$(() => {
    getMaket();
    articles();
})

//Delete User
document.addEventListener('click', e => {
    if (e.target.classList.contains('user__del')) {
        let elDel = e.target;
        elDel.closest('.user').classList.add('none');
        for (let j = 0; j < document.querySelectorAll('.user').length; j++) {
            if (document.querySelectorAll('.user')[j].classList.contains('none')) {
                deleteUser(j, document.querySelectorAll('.user'));
            }
        }
    }
})

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

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('users__addUser')) {
        $('.add').show();
    }
})

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('add__close')) {
        $('.add').hide();
    }
})

//Check.php
document.addEventListener('click', (e) => {
    if (e.target.classList.contains("add__button")) {
        let obj = {
            login: $('.login').val().trim(),
            email: $('.email').val().trim(),
            pass: $('.pass').val().trim(),
            confirm: $('.confirm').val().trim(),
            desc: $('.desc').val().trim(),
            data: $('.date').val().trim()
        }

        if (checkLogin(obj.login, '.error-login')) return;

        if (checkEmail(obj.email, '.error-email')) return;

        if (checkPass(obj.pass, '.error-pass', obj.confirm)) return;

        if (checkDateTime(obj.data, '.error-dateTime')) return;

        try {
            newUser(obj);
        } catch (e) {
            console.error(e.name, e.message);
        }
    }
})


//OpenEdit
document.addEventListener('click', e => {
    if (e.target.classList.contains('user__edit')) {
        $('.edit').show();
        $('.edit').addClass('show');
        invisible();
        let el = e.target;
        el.closest('.user').classList.add('none');
        for (let j = 0; j < document.querySelectorAll('.user').length; j++) {
            if (document.querySelectorAll('.user')[j].classList.contains('none')) {
                openWindowEdit(j, document.querySelectorAll('.user'));
                el.closest('.user').classList.remove('none');
                break;
            }
        }
    }
})

//Register
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('header__register')) {
        $('.add').show();
    }
})

//Login
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('header__login')) {
        login();
    }
})

//EnterUser
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('_enter')) {
        let obj = {
            'login': $('.edit-email').val().trim(),
            'pass': $('.edit-pass').val().trim(),
        }
        enterUser(obj);
    }
})

//Exit
document.addEventListener('click', e => {
    if (e.target.classList.contains('header__exit')) {
        exitUser();
    }
})

//Read a article
document.addEventListener('click', e => {
    if (e.target.classList.contains('article__read')) {
        let el = e.target;
        el.closest('.article__wrapper').classList.add('show');
        for (let j = 0; j < document.querySelectorAll('.article__wrapper').length; j++) {
            if (document.querySelectorAll('.article__wrapper')[j].classList.contains('show')) {
                $.ajax({
                    url: '/api/article/read',
                    method: 'POST',
                    data: {
                        'index': j,
                    },
                    success: function (response) {
                        $('.body').css({
                            "overflow": "hidden"
                        })
                        $('.header').after(response);
                    }
                })
                el.closest('.article__wrapper').classList.remove('show');
                break;
            }
        }

    }
})

//Close article
document.addEventListener('click', e => {
    let el = e.target;
    if (el.classList.contains('articleFull__close')) {
        $('.body').css({
            "overflow": "auto"
        })
        el.closest('.articleFull__wrapper').remove();
    }
})

//Fill body a random articels
document.addEventListener('click', e => {
    if (e.target.classList.contains('generator-article')) {
        $.ajax({
            url: '/api/article/random',
            method: 'POST',
            success: function (response) {
                $('.articles').append(response);
            }
        })
    }
})

//Delete Articles
document.addEventListener('click', e => {
    if (e.target.classList.contains('articleFull__delete')) {
        let el = e.target;
        el.closest('.article__wrapper').classList.add('none');
        for (let j = 0; j < document.querySelectorAll('.article__wrapper').length; j++) {
            if (document.querySelectorAll('.article__wrapper')[j].classList.contains('none')) {
                $.ajax({
                    url: '/api/article/delete',
                    method: 'DELETE',
                    data: {
                        'indexDel': j
                    },
                    success: function () {
                        $('.article__wrapper')[j].remove();
                    }
                })
            }
        }
    }
})

//Edit Article
document.addEventListener('click', e => {
    if (e.target.classList.contains('articleFull__edit')) {
        $('.edit').show();
        $('.edit').addClass('show');
        invisible();
        let el = e.target;
        el.closest('.article__wrapper').classList.add('none');
        for (let j = 0; j < document.querySelectorAll('.article__wrapper').length; j++) {
            if (document.querySelectorAll('.article__wrapper')[j].classList.contains('none')) {
                $.ajax({
                    url: '/api/article/edit',
                    type: 'POST',
                    cache: false,
                    dataType: 'html',
                    data: {
                        'indexEdit': j,
                    },
                    success(response) {
                        $('.edit').html(response);

                    }
                })
                el.closest('.article__wrapper').classList.remove('none');
                break;
            }
        }
    }
})
