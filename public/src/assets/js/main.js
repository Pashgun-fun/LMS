import {deleteUser, newUser, openWindowEdit, getUsers} from "./ajax.js";
import {checkLogin, checkEmail, checkPass, checkDateTime} from "./validation.js";
import {header} from "./components/header.js";

let indexDel, indexEdit = 0;
let userArr = $('.user');
let del = document.querySelectorAll('.user__edit');

//Delete User
let deleteItem = () => {
    document.querySelectorAll('.user__del').forEach((item, index) => {
        item.onclick = e => {
            indexDel = index;
            deleteUser(indexDel, userArr);
        }
    })
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
})

$('.add__close').on('click', () => {
    $('.add').hide();
})

deleteItem();

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
            newUser(obj, userArr, del, editItem(), deleteItem());
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

            openWindowEdit(indexEdit, userArr);
        })
    })
}

//Register
$('.header__register').on('click', e => {
    $('.add').show();
})

//Login
$('.header__login').on('click', () => {
    getUsers();
})

//Load
$(() => {
    getUsers();
    header();
})