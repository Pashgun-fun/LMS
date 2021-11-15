import {
    articles,
    deleteUser,
    newUser,
    openWindowEdit,
    getMaket,
    login,
    enterUser,
    exitUser, moveByPage,
    news,
    moveByPageNews,
} from "./ajax.js";
import {checkLogin, checkEmail, checkPass, checkDateTime, checkLength} from "./validation.js";

//Hide and show
$(() => {
    let date = new Date();

    getMaket();
    articles();

    $.ajax({
        url: '/api/check/news',
        method: 'DELETE',
        data: {
            'seconds': date.getTime() / 1000,
        },
        success: function () {
            return;
        }
    })

    news();

    history.replaceState(null, null, ' ');
})


document.addEventListener('click', e => {
    if (e.target.classList.contains('article_page_nav')) {
        document.querySelectorAll('.article_page_nav').forEach(item => {
            item.classList.remove('active');
        })
        e.target.classList.add('active');
        $('.articles').html('');
        moveByPage(e.target.getAttribute('data-page'))
    }
})


document.addEventListener('click', e => {
    if (e.target.classList.contains('news_page_nav')) {
        document.querySelectorAll('.news_page_nav').forEach(item => {
            item.classList.remove('active');
        })
        e.target.classList.add('active');
        $('.news').html('');
        moveByPageNews(e.target.getAttribute('data-page'))
    }
})

//Delete User
document.addEventListener('click', e => {
    if (e.target.classList.contains('user__del')) {
        let elDel = e.target;
        elDel.closest('.user').classList.add('none');
        let id = elDel.closest('.user').querySelector('.id__user').innerHTML;
        for (let j = 0; j < document.querySelectorAll('.user').length; j++) {
            if (document.querySelectorAll('.user')[j].classList.contains('none')) {
                deleteUser(j, document.querySelectorAll('.user'), +id);
            }
        }
    }
})

//Hide form of edit user
let invisible = () => {
    if ($('.edit').hasClass('show')) {
        document.addEventListener('click', e => {
            if (!e.target.closest('.user__edit') && !e.target.closest('.edit') && !e.target.closest('.articleFull__edit') && !e.target.closest('.newsFull__edit')) {
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
    if (e.target.classList.contains('article__add')) {
        $('.add__article').show();
    }
})

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('news__add')) {
        $('.add__news').show();
    }
})

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('add__close')) {
        $('.add').hide();
        $('.add__article').hide();
        $('.add__news').hide();
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
            descr: $('.desc').val().trim(),
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

//Add article
document.addEventListener('click', (e) => {
    if (e.target.classList.contains("add__button__article")) {
        let date = new Date();
        let obj = {
            title: $('.publish_title').val().trim(),
            user: $('.publish_login').val().trim(),
            text: $('.publish_text').val().trim(),
            date: `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`,
        }
        if (/^[a-zA-Zа-яА-Я'][a-zA-Zа-яА-Я-' ]+[a-zA-Zа-яА-Я']?$/u.test(obj.title) === false ||
            obj.title.length < 3 ||
            obj.text.length < 50) {
            return;
        }
        try {
            $.ajax({
                url: '/api/article/add',
                type: 'POST',
                cache: false,
                data: {
                    'arr': obj,
                },
                dataType: 'html',
                beforeSend: function () {
                    $('.add__button__article').prop("disabled", true);
                },
                success: function (data) {
                    $('.articles').append(data);
                    $('.add__form').trigger("reset");
                    $('.add__article').hide();
                    $('.add__button__article').prop("disabled", false);
                    location.reload();
                },
                error: function () {
                    console.log("Error");
                }
            })
        } catch (e) {
            console.error(e.name, e.message);
        }
    }
})

//Add news
document.addEventListener('click', (e) => {
    if (e.target.classList.contains("add__button__news")) {
        let date = new Date();
        let obj = {
            title: $('.news_title').val().trim(),
            user: $('.news_login').val().trim(),
            text: $('.news_text').val().trim(),
            date: `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`,
            seconds: new Date().getTime() / 1000,
        }
        if (/^[a-zA-Zа-яА-Я'][a-zA-Zа-яА-Я-' ]+[a-zA-Zа-яА-Я']?$/u.test(obj.title) === false ||
            obj.title.length < 3 ||
            obj.text.length < 50) {
            return;
        }
        try {
            $.ajax({
                url: '/api/news/add',
                type: 'POST',
                cache: false,
                data: {
                    'arr': obj,
                },
                dataType: 'html',
                beforeSend: function () {
                    $('.add__button__news').prop("disabled", true);
                },
                success: function (data) {
                    $('.news').append(data);
                    $('.add__form').trigger("reset");
                    $('.add__news').hide();
                    $('.add__button__news').prop("disabled", false);
                    location.reload();
                },
                error: function () {
                    console.log("Error");
                }
            })
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
        let id = el.closest('.user').querySelector('.id__user').innerHTML;
        for (let j = 0; j < document.querySelectorAll('.user').length; j++) {
            if (document.querySelectorAll('.user')[j].classList.contains('none')) {
                openWindowEdit(j, document.querySelectorAll('.user'), +id);
                el.closest('.user').classList.remove('none');
                break;
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
        let id = el.closest('.article__wrapper').querySelector('.id__article').innerHTML;
        for (let j = 0; j < document.querySelectorAll('.article__wrapper').length; j++) {
            if (document.querySelectorAll('.article__wrapper')[j].classList.contains('none')) {
                let arr = document.querySelectorAll('.article__wrapper');
                let koeficient;
                document.querySelectorAll('.article_page_nav').forEach(item => {
                    if (item.classList.contains('active')) {
                        koeficient = item.getAttribute('data-page');
                    }
                })
                if (+koeficient === 1) {
                    $.ajax({
                        url: '/api/window/edit/article',
                        type: 'POST',
                        cache: false,
                        dataType: 'html',
                        data: {
                            'indexEdit': j,
                            'id': +id
                        },
                        success(response) {
                            $('.edit').html(response);
                            $('.edit__button').on('click', e => {
                                e.preventDefault();
                                let objEdit = {
                                    title: $('.edit-email').val().trim(),
                                    text: $('.edit-desc').val().trim(),
                                    index: j,
                                    id: id
                                }

                                $.ajax({
                                    url: '/api/article/edit',
                                    type: 'POST',
                                    cache: false,
                                    data: {
                                        'arr': objEdit,
                                        'id': +id
                                    },
                                    dataType: 'html',
                                    beforeSend: function () {
                                        $('.edit__button').prop("disabled", true);
                                    },
                                    success: function () {
                                        $('.edit__button').prop("disabled", false);
                                        document.querySelectorAll('.article__wrapper')[objEdit.index].querySelector('.article__title').innerHTML = objEdit.title;
                                        document.querySelectorAll('.article__wrapper')[objEdit.index].querySelector('.article__text').innerHTML = checkLength(objEdit.text);
                                    }
                                })

                                $('.edit').hide();
                            })
                        },
                        error: function () {
                            alert("Редактирование не возможно");
                        }
                    })
                }

                if (+koeficient !== 1) {
                    $.ajax({
                        url: '/api/window/edit/article',
                        type: 'POST',
                        cache: false,
                        dataType: 'html',
                        data: {
                            'indexEdit': 6 * koeficient - (6 - j),
                            'id': +id
                        },
                        success(response) {
                            $('.edit').html(response);
                            $('.edit__button').on('click', e => {
                                e.preventDefault();
                                let objEdit = {
                                    title: $('.edit-email').val().trim(),
                                    text: $('.edit-desc').val().trim(),
                                    index: 6 * koeficient - (6 - j),
                                    id: id
                                }

                                $.ajax({
                                    url: '/api/article/edit',
                                    type: 'POST',
                                    cache: false,
                                    data: {
                                        'arr': objEdit,
                                        'id': +id
                                    },
                                    dataType: 'html',
                                    beforeSend: function () {
                                        $('.edit__button').prop("disabled", true);
                                    },
                                    success: function () {
                                        $('.edit__button').prop("disabled", false);
                                        document.querySelectorAll('.article__wrapper')[j].querySelector('.article__name').innerHTML = objEdit.user;
                                        document.querySelectorAll('.article__wrapper')[j].querySelector('.article__title').innerHTML = objEdit.title;
                                        document.querySelectorAll('.article__wrapper')[j].querySelector('.article__text').innerHTML = checkLength(objEdit.text);
                                    }
                                })

                                $('.edit').hide();
                            })
                        },
                        error: function () {
                            alert("Редактирование не возможно");
                        }
                    })
                }
                el.closest('.article__wrapper').classList.remove('none');
                break;
            }
        }
    }
})

//Edit News
document.addEventListener('click', e => {
    if (e.target.classList.contains('newsFull__edit')) {
        $('.edit').show();
        $('.edit').addClass('show');
        invisible();
        let el = e.target;
        el.closest('.news__wrapper').classList.add('none');
        let id = el.closest('.news__wrapper').querySelector('.id__news').innerHTML;
        for (let j = 0; j < document.querySelectorAll('.news__wrapper').length; j++) {
            if (document.querySelectorAll('.news__wrapper')[j].classList.contains('none')) {
                let arr = document.querySelectorAll('.news__wrapper');
                let koeficient;
                document.querySelectorAll('.news_page_nav').forEach(item => {
                    if (item.classList.contains('active')) {
                        koeficient = item.getAttribute('data-page');
                    }
                })
                if (+koeficient === 1) {
                    $.ajax({
                        url: '/api/window/edit/news',
                        type: 'POST',
                        cache: false,
                        dataType: 'html',
                        data: {
                            'indexEdit': j,
                            'id': +id
                        },
                        success(response) {
                            $('.edit').html(response);
                            $('.edit__button').on('click', e => {
                                e.preventDefault();
                                let objEdit = {
                                    title: $('.edit-email').val().trim(),
                                    text: $('.edit-desc').val().trim(),
                                    index: j,
                                    id: +id
                                }

                                $.ajax({
                                    url: '/api/news/edit',
                                    type: 'POST',
                                    cache: false,
                                    data: {
                                        'arr': objEdit,
                                        'id': +id
                                    },
                                    dataType: 'html',
                                    beforeSend: function () {
                                        $('.edit__button').prop("disabled", true);
                                    },
                                    success: function () {
                                        $('.edit__button').prop("disabled", false);
                                        document.querySelectorAll('.news__wrapper')[objEdit.index].querySelector('.news__title').innerHTML = objEdit.title;
                                        document.querySelectorAll('.news__wrapper')[objEdit.index].querySelector('.news__text').innerHTML = checkLength(objEdit.text);
                                    }
                                })

                                $('.edit').hide();
                            })
                        },
                        error: function () {
                            alert("Редактирование не возможно");
                        }
                    })
                }
                if (+koeficient !== 1) {
                    $.ajax({
                        url: '/api/window/edit/news',
                        type: 'POST',
                        cache: false,
                        dataType: 'html',
                        data: {
                            'indexEdit': 6 * koeficient - (6 - j),
                            'id': +id
                        },
                        success(response) {
                            $('.edit').html(response);
                            $('.edit__button').on('click', e => {
                                e.preventDefault();
                                let objEdit = {
                                    title: $('.edit-email').val().trim(),
                                    text: $('.edit-desc').val().trim(),
                                    index: 6 * koeficient - (6 - j),
                                    id: id
                                }

                                $.ajax({
                                    url: '/api/news/edit',
                                    type: 'POST',
                                    cache: false,
                                    data: {
                                        'arr': objEdit,
                                    },
                                    dataType: 'html',
                                    beforeSend: function () {
                                        $('.edit__button').prop("disabled", true);
                                    },
                                    success: function () {
                                        $('.edit__button').prop("disabled", false);
                                        document.querySelectorAll('.news__wrapper')[j].querySelector('.news__user').innerHTML = objEdit.user;
                                        document.querySelectorAll('.news__wrapper')[j].querySelector('.news__title').innerHTML = objEdit.title;
                                        document.querySelectorAll('.news__wrapper')[j].querySelector('.news__text').innerHTML = checkLength(objEdit.text);
                                    }
                                })

                                $('.edit').hide();
                            })
                        },
                        error: function () {
                            alert("Редактирование не возможно");
                        }
                    })
                }
                el.closest('.news__wrapper').classList.remove('none');
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
                let koeficient;
                document.querySelectorAll('.article_page_nav').forEach(item => {
                    if (item.classList.contains('active')) {
                        koeficient = item.getAttribute('data-page');
                    }
                })
                if (+koeficient !== 1) {
                    $.ajax({
                        url: '/api/article/read',
                        method: 'POST',
                        data: {
                            'index': 6 * koeficient - (6 - j),
                        },
                        success: function (response) {
                            $('.body').css({
                                "overflow": "hidden"
                            })
                            $('.header').after(response);
                        }
                    })
                    return;
                }
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
        history.replaceState(null, null, ' ');
        el.closest('.articleFull__wrapper').remove();
    }
})

//Delete Articles
document.addEventListener('click', e => {
    if (e.target.classList.contains('articleFull__delete')) {
        let el = e.target;
        el.closest('.article__wrapper').classList.add('none');
        let id = el.closest('.article__wrapper').querySelector('.id__article').innerHTML;
        for (let j = 0; j < document.querySelectorAll('.article__wrapper').length; j++) {
            if (document.querySelectorAll('.article__wrapper')[j].classList.contains('none')) {
                let koeficient;
                document.querySelectorAll('.article_page_nav').forEach(item => {
                    if (item.classList.contains('active')) {
                        koeficient = item.getAttribute('data-page');
                    }
                })
                if (+koeficient === 1) {
                    $.ajax({
                        url: '/api/article/delete',
                        method: 'POST',
                        data: {
                            'indexDel': j,
                            'id': +id,
                        },
                        success: function () {
                            $('.article__wrapper')[j].remove();
                        }
                    })
                }

                if (+koeficient !== 1) {
                    $.ajax({
                        url: '/api/article/delete',
                        method: 'POST',
                        data: {
                            'indexDel': 6 * koeficient - (6 - j),
                            'id': +id,
                        },
                        success: function () {
                            $('.article__wrapper')[j].remove();
                        }
                    })
                }
            }
        }
    }
})

//DeleteNews
document.addEventListener('click', e => {
    if (e.target.classList.contains('newsFull__delete')) {
        let el = e.target;
        el.closest('.news__wrapper').classList.add('none');
        let id = el.closest('.news__wrapper').querySelector('.id__news').innerHTML;
        for (let j = 0; j < document.querySelectorAll('.news__wrapper').length; j++) {
            if (document.querySelectorAll('.news__wrapper')[j].classList.contains('none')) {
                let koeficient;
                document.querySelectorAll('.news_page_nav').forEach(item => {
                    if (item.classList.contains('active')) {
                        koeficient = item.getAttribute('data-page');
                    }
                })
                if (+koeficient !== 1) {
                    $.ajax({
                        url: '/api/news/delete',
                        method: 'POST',
                        data: {
                            'indexDel': 6 * koeficient - (6 - j),
                            'id': +id
                        },
                        success: function () {
                            $('.news__wrapper')[j].remove();
                        }
                    })
                    return;
                }
                $.ajax({
                    url: '/api/news/delete',
                    method: 'POST',
                    data: {
                        'indexDel': j,
                        'id': +id
                    },
                    success: function () {
                        $('.news__wrapper')[j].remove();
                    }
                })
            }
        }
    }
})

//ReadNews
document.addEventListener('click', e => {
    if (e.target.classList.contains('news__read')) {
        let el = e.target;
        el.closest('.news__wrapper').classList.add('show');
        for (let j = 0; j < document.querySelectorAll('.news__wrapper').length; j++) {
            if (document.querySelectorAll('.news__wrapper')[j].classList.contains('show')) {
                let koeficient;
                document.querySelectorAll('.news_page_nav').forEach(item => {
                    if (item.classList.contains('active')) {
                        koeficient = item.getAttribute('data-page');
                    }
                })
                if (+koeficient !== 1) {
                    $.ajax({
                        url: '/api/news/read',
                        method: 'POST',
                        data: {
                            'index': 6 * koeficient - (6 - j),
                        },
                        success: function (response) {
                            $('.header').after(response);
                            $('.body').css({
                                "overflow": "hidden"
                            })

                        }
                    })
                    return
                }
                $.ajax({
                    url: '/api/news/read',
                    method: 'POST',
                    data: {
                        'index': j,
                    },
                    success: function (response) {
                        $('.header').after(response);
                        $('.body').css({
                            "overflow": "hidden"
                        })

                    }
                })
                el.closest('.news__wrapper').classList.remove('show');
                break;
            }
        }

    }
})

$(window).bind('hashchange', function () {
    let hash = window.location.hash;
    hash = hash.substring(1);
    hash = hash.split('/');
    let index = hash.pop();
    if (index.match(/^\d*\.?\d+\$?$/)) {
        $.ajax({
            url: '/api/news/old',
            method: 'POST',
            data: {
                'index': index,
            },
            success: function (response) {
                $('.header').after(response);
                $('.body').css({
                    "overflow": "hidden"
                })
            }
        })
    }
    return;
})

document.addEventListener('click', e => {
    if (e.target.classList.contains('products') || e.target.classList.contains('product_search_all')) {
        $.ajax({
            url: '/api/product/get',
            method: 'GET',
            success: function (response) {
                document.querySelector('.product_menu').style.display = "grid";
                document.querySelector('.product_search').style.display = "block";
                $('.products_body').html(response);
            }
        })
    }
})

document.addEventListener('click', e => {
    if (e.target.classList.contains('product_search_button')) {
        let value = $('.search_product').val();
        console.log(typeof value)
        if (value.length !== 0) {
            $.ajax({
                url: '/api/products/search',
                method: 'POST',
                data: {
                    'value': value
                },
                success: function (response) {
                    $('.products_body').html(response);
                }
            })
        }
    }
})

document.addEventListener('click', e => {
    if (e.target.classList.contains('product_search_subchapter')) {
        let value = $('.search_product_subchapter').val();
        console.log(typeof value)
        if (value.length !== 0) {
            $.ajax({
                url: '/api/products/search/subchapter',
                method: 'POST',
                data: {
                    'value': value
                },
                success: function (response) {
                    // $('.products_body').html(response);
                }
            })
        }
    }
})

document.addEventListener('click', e => {
    if (e.target.classList.contains('product_search_brend')) {
        let value = $('.search_product_brend').val();
        if (value.length !== 0) {
            $.ajax({
                url: '/api/products/search/brend',
                method: 'POST',
                data: {
                    'value': value
                },
                success: function (response) {
                    $('.products_body').html(response);
                }
            })
        }
    }
})

document.addEventListener('click', e => {
    if (e.target.classList.contains('product_search_model')) {
        let value = $('.search_product_model').val();
        console.log(typeof value)
        if (value.length !== 0) {
            $.ajax({
                url: '/api/products/search/model',
                method: 'POST',
                data: {
                    'value': value
                },
                success: function (response) {
                    $('.products_body').html(response);
                }
            })
        }
    }
})

document.addEventListener('click', e => {
    if (e.target.classList.contains('product_search_color')) {
        let value = $('.search_product_color').val();
        console.log(typeof value)
        if (value.length !== 0) {
            $.ajax({
                url: '/api/products/search/color',
                method: 'POST',
                data: {
                    'value': value
                },
                success: function (response) {
                    $('.products_body').html(response);
                }
            })
        }
    }
})

let filterByChapter = $('.product_filterByChapter')

filterByChapter.on('click', (e) => {
    let value = $('.search_product_brend').val();
    if (!e.target.classList.contains('filtered')) {
        $.ajax({
            url: '/api/products/filter/chapter/straight',
            method: 'POST',
            data: {
                'value': value
            },
            success: function (response) {
                e.target.classList.add('filtered');
                $('.products_body').html(response);
            }
        })
        return;
    }
    $.ajax({
        url: '/api/products/filter/chapter/back',
        method: 'POST',
        data: {
            'value': value
        },
        success: function (response) {
            e.target.classList.remove('filtered');
            $('.products_body').html(response);
        }
    })
})

let searchAll = $('.product_search_allCategories');

searchAll.on('click', e => {
    let arr = {
        'chapter': $('.search_product').val().trim(),
        'subchapter': $('.search_product_subchapter').val().trim(),
        'brend': $('.search_product_brend').val().trim(),
        'model': $('.search_product_model').val().trim(),
        'color': $('.search_product_color').val().trim()
    }
    $.ajax({
        url: '/api/products/search/all',
        method: 'POST',
        data: {
            'arr': JSON.stringify(arr),
        },
        success: function (response) {
            $('.products_body').html(response);
        }
    })
})