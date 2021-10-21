<?php

namespace core;

use models\User;

class Authorization extends Model
{
    protected Helper $helper;
    public ?string $directory = null;

    function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/";
    }

    public function getAuthorization(User $user): ?string
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        foreach ($arr as $file) {
            $el = parent::readFile($this->directory . $file);
            if ($el['email'] === $user->getLogin() && $el['pass'] === $user->getPass()) {
                if ($el['role'] === "admin") {
                    $_SESSION['ROLE'] = 'admin';
                    return $user->getLogin();
                }
                $_SESSION['ROLE'] = 'user';
                return $user->getLogin();
            }
        }
        return null;
    }
}