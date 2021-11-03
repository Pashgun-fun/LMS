<?php

namespace core;

use controllers\ArticleController;
use controllers\NewController;
use enums\Roles;

class Middleware
{
    protected string $path;

    function __construct()
    {
        $this->path = htmlspecialchars($_SERVER['REQUEST_URI']);
    }

    /**
     * Функиця для порверки прав доступа
     */
    public function check()
    {
        switch ($this->path) {
            case "/api/user/login":
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    header("Location: /");
                }
                break;
            case "/api/user/delete":
            case "/api/user/edit":
            case "/api/window/edit":
            case "/api/article/delete":
            case "/api/news/delete":
            case "/api/window/edit/article":
            case "/api/article/edit":
            case "/api/window/edit/news":
            case "/api/news/edit":
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    header("Location: /");
                }
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                break;
            case "/api/user/get":
            case "/api/user/exit":
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                break;
            case "/api/articles":
                $controllerArticle = new ArticleController();
                if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    $controllerArticle->printShortsArticles();
                }
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::ADMIN_ROLE) {
                    $controllerArticle->printShortArticlesForAdmin();
                }
                break;
            case "/api/news":
                $controllerNews = new NewController();
                if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    $controllerNews->printShortsNews();
                }
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::ADMIN_ROLE) {
                    $controllerNews->printShortNewsForAdmin();
                }
                break;
            case "/api/article/add":
                $controllerArticle = new ArticleController();
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    $controllerArticle->newArticle();
                }
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::ADMIN_ROLE) {
                    $controllerArticle->newArticleAdmin();
                }
                break;
            case "/api/news/add":
                $controllerNews = new NewController();
                if (!isset($_SESSION['ROLE'])) {
                    header("Location: /");
                }
                if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    $controllerNews->newNews();
                }
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::ADMIN_ROLE) {
                    $controllerNews->newNewsAdmin();
                }
                break;
            case "/api/articles/pagination":
                $controllerArticle = new ArticleController();
                if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    $controllerArticle->pagination();
                }
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::ADMIN_ROLE) {
                    $controllerArticle->paginationAdmin();
                }
                break;
            case "/api/news/pagination":
                $controllerNews = new NewController();
                if (!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] === Roles::USER_ROLE) {
                    $controllerNews->pagination();
                }
                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] === Roles::ADMIN_ROLE) {
                    $controllerNews->paginationAdmin();
                }
                break;
        }
    }
}
