let checkLogin = (login, el) => {
    if (/^[a-zA-Z][a-zA-Z0-9-_\.]{4,20}$/.test(login) == false) {//с ограничением 2-20 символов, которыми могут быть буквы и цифры, первый символ обязательно буква
        $(el).html("Должен состоять не менее, чем из 4 символов и только из букви цифр и латиница");
        return true;
    }
    if (/[a-zA-Z]/.test(login[0]) == false) {
        $(el).html("Логин должен начинаться с буквы");
        return true;
    }
    $(el).html("");
}

let checkEmail = (email, el) => {
    if (/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/.test(email) == false) {
        $(el).html('Введите email');
        return true;
    }
    $(el).html("");
}

let checkPass = (pass, el, confirm) => {
    if (pass.length < 8) {//Строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов
        $(el).html("Слишком короткий пароль");
        return true;
    }
    if (pass !== confirm) {
        $(el).html("Пароли не совпадают");
        return true;
    }
    if (/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/.test(pass) == false) {
        $(el).html("Строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов");
        return true;
    }
    $(el).html("");
}

let checkDateTime = (newDate, el) => {
    if (/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(newDate) == false) {
        $(el).html("Введите корректную дату");
        return true;
    }
    let parseDate = newDate.split('.');
    for (let i = 0; i < parseDate.length; i++) {
        if (i == 1) {
            parseDate[i] = +parseDate[i] - 1;
        }
        parseDate[i] = +parseDate[i];
    }
    parseDate.reverse();
    let d = new Date(...parseDate);
    let today = new Date();
    if (today.getTime() - d.getTime() < 567648000000) {
        $(el).html("Вам меньше 18, попробуйте позже");
        return true;
    }
}

let checkLength = (string) => {
    let sum = 0;
    let arrWords = [];

    let arrOfWords = string.split(" ")

    for (let j = 0; j < arrOfWords.length; j++) {
        sum += arrOfWords[j].length;
        if (sum > 90) {
            return arrWords.join(" ") + ' ...';
        }
        arrWords.push(arrOfWords[j]);
    }
    return '';
}

export {checkLogin, checkEmail, checkPass, checkDateTime, checkLength};