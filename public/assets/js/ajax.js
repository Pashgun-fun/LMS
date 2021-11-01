import {checkLogin, checkPass, checkEmail} from "./validation.js";

let deleteUser = (indexDel, userArr) => {
    $.ajax({
        url: '/api/user/delete',
        type: 'DELETE',
        cache: false,
        data: {
            'indexDel': indexDel,
        },
        success: function () {
            userArr[indexDel].remove();
        },
        error: function () {
            alert("Удаление прошло безуспешно");
        }

    })
}

let newUser = (obj) => {
    $.ajax({
        url: '/api/user/add',
        type: 'POST',
        cache: false,
        data: {
            'arr': obj,
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
        },
        error: function () {
            console.log("Error");
        }
    })
}

let editUser = (objEdit, userArr) => {
    $.ajax({
        url: '/api/user/edit',
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
            userArr[objEdit.index].querySelector('.user__name').innerHTML = objEdit.login;
        }
    })
}

let openWindowEdit = (indexEdit, userArr) => {
    let arr = userArr;
    $.ajax({
        url: '/api/window/edit',
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

                let objEdit = {
                    login: $('.edit-login').val().trim(),
                    email: $('.edit-email').val().trim(),
                    pass: $('.edit-pass').val().trim(),
                    confirm: $('.edit-confirm').val().trim(),
                    descr: $('.edit-desc').val().trim(),
                    index: indexEdit,
                }

                if (checkLogin(objEdit.login, '.error-login-edit')) return;

                if (checkEmail(objEdit.email, '.error-email-edit')) return;

                if (checkPass(objEdit.pass, '.error-pass-edit', objEdit.confirm)) return;

                editUser(objEdit, arr)

                $('.edit').hide();
            })
        },
        error: function () {
            alert("Редактирование не возможно");
        }
    })
}

let getMaket = () => {
    $.ajax({
        url: "/",
        method: "GET",
        success: function (response) {
            return response;
        }
    })
}

let login = () => {
    $.ajax({
        url: '/api/user/login',
        method: 'GET',
        success: function (response) {
            $('.articles').remove();
            $('.news').remove();
            $('._title').remove();
            $('.article_pages').remove();
            $('.news_pages').remove();
            $('.header').after(response);
        }
    })
}

let enterUser = (obj) => {
    $.ajax({
        url: "/api/user/role",
        method: 'POST',
        cache: false,
        dataType: 'html',
        beforeSend: function () {
            $('._enter').prop("disabled", true);
        },
        data: {
            'arr': obj,
        },
        success: function (response) {
            location.reload();
            return response;
        }
    })
}

let exitUser = () => {
    $.ajax({
        url: "/api/user/exit",
        method: 'POST',
        cache: false,
        dataType: 'html',
        success: function (response) {
            location.reload();
            $('.body').html(response);
        }
    })
}

let articles = () => {
    $.ajax({
        url: "/api/articles",
        method: "POST",
        success: function (response) {
            $('.articles').append(response);
            $('.article_pages').html('');
            let articlesArr = $('.article__wrapper').length + 1;
            $('.count_articles').html(articlesArr - 1);
            let countPerPage = Math.ceil(articlesArr / 6);
            for (let j = 1; j <= countPerPage; j++) {
                let page = `<span class="article_page_nav" data-page = '${j}'>${j}</span>`;
                $('.article_pages').append(page);
            }
            $('.article_page_nav')[0].classList.add('active');
            $('.articles').html('');
            moveByPage()
        }
    })
}

let moveByPage = (page = 1) => {
    $.ajax({
        url: '/api/articles/pagination',
        method: 'POST',
        dataType: 'html',
        data: {
            'page': page,
        },
        success: function (response) {
            $('.articles').append(response);
        }
    })
}

let news = () => {
    $.ajax({
        url: "/api/news",
        method: "POST",
        success: function (response) {
            $('.news').append(response);
            $('.news_pages').html('');
            let articlesArr = $('.news__wrapper').length + 1;
            $('.count_news').html(articlesArr - 1);
            let countPerPage = Math.ceil(articlesArr / 6);
            for (let j = 1; j <= countPerPage; j++) {
                let page = `<span class="news_page_nav" data-page = '${j}'>${j}</span>`;
                $('.news_pages').append(page);
            }
            $('.news_page_nav')[0].classList.add('active');
            $('.news').html('');
            moveByPageNews();
        }
    })
}

let moveByPageNews = (page = 1) => {
    $.ajax({
        url: '/api/news/pagination',
        method: 'POST',
        dataType: 'html',
        data: {
            'page': page,
        },
        success: function (response) {
            $('.news').append(response);
        }
    })
}

export {
    moveByPageNews,
    news,
    moveByPage,
    articles,
    deleteUser,
    newUser,
    openWindowEdit,
    getMaket,
    login,
    enterUser,
    exitUser
}
