import {deleteUser, newUser, openWindowEdit, getUsers, getMaket, login} from "./ajax.js";
import {checkLogin, checkEmail, checkPass, checkDateTime} from "./validation.js";

//Hide and show
$(() => {
    getUsers();
    getMaket();
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

$('.users__addUser').on('click', () => {
    $('.add').show();
})

$('.add__close').on('click', () => {
    $('.add').hide();
})

//Check.php
$(() => {
    $('.add__button').on('click', (e) => {

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
    })
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
                break;
            }
        }
    }
})

//Register
$('.header__register').on('click', e => {
    $('.add').show();
})

//Login
$('.header__login').on('click', () => {
    login();
})

//Logo link
$('.header__logo').on('click', () => {
    let p = new Promise((resolve, reject) => {
        $('.app').html('');
        resolve();
    })
    p.then(() => {
        $('.app').prepend("<section class=\"users\">\n" +
            "        <div class=\"users__wrapper _container\">\n" +
            "            <div class=\"users__add\">\n" +
            "                <div class=\"users__addTitle\">Добавить нового пользователя</div>\n" +
            "                <a class=\"users__addUser _button\"></a>\n" +
            "            </div>\n" +
            "\n" +
            "        </div>\n" +
            "    </section>");
    }).then(() => {
        $('.app').append(getUsers());
    })
})
