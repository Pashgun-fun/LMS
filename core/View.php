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

    public function user(string $name)
    {
        include __DIR__ . "/../views/user.php";
    }

    public function userPage(array $usersNameArr)
    {
        include __DIR__ . "/../views/User/userPage.php";

    }

    public function adminPage(array $usersNameArr)
    {
        include __DIR__ . "/../views/Admin/adminPage.php";
    }

    public function article(string $title, string $text, string $user, string $date)
    {
        include __DIR__ . "/../views/article.php";
    }

    public function cardArticle(string $title, string $text, string $user, string $date)
    {
        include __DIR__ . "/../views/cardArticle.php";
    }

    public function articleAdmin(string $title, string $text, string $user, string $date)
    {
        include __DIR__ . "/../views/articleAdmin.php";
    }

    public function news(string $title, string $text, string $user, string $date)
    {
        include __DIR__ . "/../views/news.php";
    }

}