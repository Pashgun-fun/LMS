

class Page{
    constructor() {
        this.login = $('.login').val().trim();
        this.email = $('.email').val().trim();
        this.pass = $('.pass').val().trim();
        this.confirm = $('.confirm').val().trim();
        this.desc = $('.desc').val().trim();
    }
    invisible(){
        $(() => {
            $('.add').hide();
            this.deleteItem();
        })
    }
    showAddWindow(){
        $('.users__addUser').on('click', () => {
            $('.add').show();
        })
    }
    closeAddWindow(){
        $('.add__close').on('click', () => {
            $('.add').hide();
        })
    }
    deleteItem(){
        document.querySelectorAll('.user').forEach(item => {
            item.addEventListener('click', e => {
                if (e.target.className == "user__del _button") {
                    item.style.display = "none";
                    item.classList.add("delete");
                }
            })
        })
    }
    ajaxForAdd(){
        $(() => {
            $('.add__button').on('click', (e) => {
                if (this.login.length < 3) {
                    $('.error-login').text("Не менее 3 символов");
                    return false;
                } else if (this.email.length < 3) {
                    $('.error-email').text("Введите email");
                    return false;
                } else if (this.pass != confirm) {
                    $('.error-pass').text("Пароли должны совпадать");
                    return false;
                }

                $('._error').text("");

                $.ajax({
                    url: './check.php',
                    type: 'POST',
                    cache: false,
                    data: {
                        'login': this.login,
                        'email': this.email,
                        'pass': this.pass,
                        'desc': this.desc,
                    },
                    dataType: 'html',
                    beforeSend: function (){
                        $('.add__button').prop("disabled", true);
                    },
                    success: function (data) {
                        $('.users__wrapper').append(data);
                        $('.add__form').trigger("reset");
                        $('.add__form').hide();
                        $('.add__button').prop("disabled", false);
                        this.deleteItem();
                    }
                })
            })
        })
    }
}
