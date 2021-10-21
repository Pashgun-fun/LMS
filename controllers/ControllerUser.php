<?php

namespace controllers;

use core\Controller;
use core\Helper;
use core\Validation;
use models\User;
use models\UserModel;
use core\Authorization;

class ControllerUser extends Controller
{
    protected UserModel $model;
    protected Authorization $auth;
    protected Helper $helper;

    function __construct()
    {
        $this->helper = new Helper();
        $this->auth = new Authorization();
        parent::__construct();
        $this->model = new UserModel();
    }

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

    public function deleteUser()
    {
        $arr = $this->helper->resetAPI();
        var_dump($arr);
        $this->model->deleteUser($arr['indexDel']);
    }

    public function allUsers()
    {
        $usersNameArr = $this->model->getAllUsers();
        foreach ($usersNameArr as $name) {
            $this->view->user(trim($name));
        }
    }

    public function sortUsers()
    {
        $this->model->sortUsers();
    }

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

    public function authorization()
    {
        $user = new User($_POST['arr']);
        $this->auth->getAuthorization($user);
    }

    public function checkRole(): void
    {
        if (!isset($_SESSION['ROLE'])) {
            $this->view->guestPage();
            return;
        }
        switch ($_SESSION['ROLE']) {
            case 'user':
                $usersNameArr = $this->model->getAllUsers();
                $this->view->userPage($usersNameArr);
                break;
            case 'admin':
                $usersNameArr = $this->model->getAllUsers();
                $this->view->adminPage($usersNameArr);
                break;
        }

    }

    public function exitFromProfile()
    {
        session_destroy();
        $this->view->guestPage();
    }
}