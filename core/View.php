<?php

namespace core;

class View
{
    public function editWindow(string $name, string $email, string $desc)
    {
        include __DIR__ . "/../views/edit_Window.php";
    }

    public function guestPage()
    {
        include __DIR__ . "/../views/Guest/guestPage.php";
    }

    public function login()
    {
        include __DIR__ . "/../views/login.php";
    }

    public function user($name)
    {
        include __DIR__ . "/../views/user.php";
    }

    public function userPage($usersNameArr)
    {
        include __DIR__ . "/../views/User/userPage.php";

    }

    public function adminPage($usersNameArr)
    {
        include __DIR__ . "/../views/Admin/adminPage.php";
    }

    public function Article($title, $text, $user, $date){
        include __DIR__."/../views/Article.php";
    }
}