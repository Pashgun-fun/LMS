import {deleteUser, newUser, openWindowEdit, getMaket, login, enterUser, exitUser} from "./ajax.js";
import {checkLogin, checkEmail, checkPass, checkDateTime} from "./validation.js";

//Hide and show
$(() => {
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