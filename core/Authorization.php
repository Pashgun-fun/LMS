<?php

namespace core;

use entites\User;
use enums\Roles;

class Authorization extends Model
{
    protected Helper $helper;
    public ?string $directory = null;
    protected Roles $roles;

    function __construct()
    {
        $this->roles = new Roles();
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/Users/";
    }

    /**
     * Авторизция пользователя с проверкой существует ли такой пользователя
     * Если он существует добавляем ему роль в сессию  для дальнейшей проверки прав доступа
     * Если такого пользователя нету, выход из авторизации
     **/
    public function getAuthorization(User $user): ?string
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        foreach ($arr as $file) {
            $el = parent::readFile($this->directory . $file);
            if ($el['email'] === $user->getLogin() && $el['pass'] === $user->getPass()) {
                if ($el['role'] === "admin") {
                    $_SESSION['ROLE'] = $this->roles::ADMIN_ROLE;
                    $_SESSION['id'] = $file;
                    return $user->getLogin();
                }
                $_SESSION['ROLE'] = $this->roles::USER_ROLE;
                $_SESSION['id'] = $file;
                return $user->getLogin();
            }
        }
        return null;
    }
}