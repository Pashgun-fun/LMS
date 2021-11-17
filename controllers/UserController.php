<?php

namespace controllers;

use core\Controller;
use core\Helper;
use core\Validation;
use entites\User;

use models\UserModel;
use models\sqlModels\SqlUserModel;

class UserController extends Controller
{
    protected $model;
    protected Helper $helper;
    protected array $config;

    function __construct()
    {
        parent::__construct();
        $this->helper = new Helper();
        $this->config = require_once __DIR__ . "/../config/validation_check.php";
        switch (gettype($this->connect)) {
            case "array":
                $this->model = UserModel::getInstance();
                break;
            case "object":
                $this->model = SqlUserModel::getInstance();
                break;
        }
    }

    /**
     * Добавление нового пользователя в базу данных (файлы)
     * Если есть право доступа, то этот пользователь сразу отображается (доступно только для админа сейчас)
     * Создание проиисходит через сущность User,  если прошла валидация обязательных полей
     **/
    public function newUser()
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkFields');
        if (count($arr) !== 0) {
            return;
        }
        $user = new User($_POST['arr']);
        $userName = $this->model->newUser($user);
        $this->view->user($userName);
    }

    /**
     * Удаление пользователя из базы данных (т.е. удаление файла)
     * Реализовано через ResetAPI  и HTTP метод DELETE
     **/
    public function deleteUser()
    {
        $this->model->deleteUser($_POST['indexDel'], $_POST['id']);
    }

    /**
     * Отображение всех пользователей для авторизированных пользователей
     * Доступно для User и Admin
     **/
    public function allUsers()
    {
        $usersNameArr = $this->model->getAllUsers();
        foreach ($usersNameArr as $name) {
            $this->view->user(trim($name));
        }
    }

    /**
     * Редактирование пользователя
     * занесение новых данных полей в файл из базы данных для соответствующего пользователя
     * Редактирование происходит через работу с ссущностью  User, если прошла валидация обязательных полей
     **/
    public function editUser(): void
    {
        $valid = new Validation();
        $arr = $valid->checkCreateForm($_POST['arr'], 'checkEditFields');
        if (count($arr) !== 0) {
            return;
        }
        $user = new User($_POST['arr']);
        $this->model->editUser($user);
    }

    /**
     * Авторизаци пользователя
     */
    public function authorization()
    {
        $user = new User($_POST['arr']);
        $arr = $this->model->getAuthorization($user);
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                if (in_array($key, $this->config['checkSession'])) {
                    $_SESSION[$key] = $value;
                }
            }
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Проверка роли пользователя при перезапуске страницы
     * Отображение главной страницы в зависимости от роли пользователя
     **/
    public function checkRole(): void
    {
        if (!isset($_SESSION['ROLE'])) {
            $this->view->guestPage();
            return;
        }
        switch ($_SESSION['ROLE']) {
            case 'user':
                $usersNameArr = $this->model->getAllUsers();
                $this->view->userPage($usersNameArr, $_SESSION['NAME']);
                break;
            case 'admin':
                $usersNameArr = $this->model->getAllUsers();
                $this->view->adminPage($usersNameArr, $_SESSION['NAME']);
                break;
        }

    }

    /**
     * Выход из профиля
     */
    public function exitFromProfile()
    {
        session_destroy();
        $this->view->guestPage();
    }
}