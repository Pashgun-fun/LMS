<?php

namespace models;

use core\Model;
use entites\User;
use enums\Roles;

class UserModel extends Model
{

    protected static ?UserModel $instance = null;
    protected Roles $roles;

    public function __construct()
    {
        parent::__construct();
        $this->roles = new Roles();
    }

    /**
     * Закрываем подключение к БД
     */
    public function __destruct()
    {
        switch (gettype($this->connect)) {
            case "object":
            {
                $this->connect->close();
            }
        }
    }

    /**
     * Singleton
     * Чтобы объект не создавалася несолько раз один и тот же
     * а использовался один и тот же, если он уже создан
     */
    public static function getInstance(): UserModel
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Создание нового пользователя с получением данных из ajax
     * Первоначально проверяется, существет ли уже пользватель  с таким email,
     * если существет новый пользователь не создастя
     * Если всё успешно, записывается в файл и жобавляется в базу данных
     * Если достаточно прав доступа то пользователь сразу отображается на экране (доступно только для admin)
     **/
    public function newUser(User $user): string
    {
        switch (gettype($this->connect)) {
            case "object":
                $query = "INSERT INTO homestead.Users VALUES (
                                    null, 
                                    '{$user->getLogin()}', 
                                    '{$user->getEmail()}', 
                                    '{$user->getPass()}', 
                                    '{$user->getData()}',
                                    '{$user->getDesc()}',
                                    'user')";
                $this->connect->query($query);
                return $user->getLogin();
            case "array":
                $arrayFiles = $this->helper->myscandir(__DIR__ . $this->connect['file']['users']);
                asort($arrayFiles);
                foreach ($arrayFiles as $file) {
                    $fileName = __DIR__ . $this->connect['file']['users'] . $file;
                    $el = $this->readFile($fileName);
                    if ($user->getEmail() === $el['email']) {
                        return '';
                    }
                }

                $userData = array(
                    'login' => $user->getLogin(),
                    'email' => $user->getEmail(),
                    'desc' => $user->getDesc(),
                    'data' => $user->getData(),
                    'pass' => $user->getPass(),
                );

                $newFile = __DIR__ . $this->connect['file']['users'] . (+array_pop($arrayFiles) + 1);
                $this->writeFile($newFile, $userData);
                return $user->getLogin();
        }
        return '';
    }

    /**
     * Удаление пользователя из базы данных, удаление соответсвующего файла
     * Удаление блока с пользоватлем происходит на frontend
     **/
    public function deleteUser(int $indexDel)
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.Users");
                $allUsers = [];
                $delID = null;
                while ($row = $result->fetch_assoc()) {
                    array_push($allUsers, $row);
                }
                foreach (array_values($allUsers) as $key => $value) {
                    if ($key === $indexDel) {
                        $delID = (int)$value['ID'];
                        break;
                    }
                }
                $this->connect->query("DELETE FROM homestead.Users WHERE ID = '{$delID}'");
                break;
            case "array":
                $this->delete(__DIR__ . $this->connect['file']['users'], $indexDel);
                break;
        }
    }

    /**
     * Редактирование пользователя по которому произошло событие нажатия
     * Обрабатывается по уникальному индексу этого пользователя
     * Получаем новые данные о пользователе и менем их в его файле
     **/
    public function editUser(User $user)
    {
        switch (gettype($this->connect)) {
            case "object":
                $result = $this->connect->query("SELECT * FROM homestead.Users");
                $allUsers = [];
                $editID = null;
                while ($row = $result->fetch_assoc()) {
                    array_push($allUsers, $row);
                }
                foreach (array_values($allUsers) as $key => $value) {
                    if ($key === $user->getIndex()) {
                        $editID += (int)$value['ID'];
                        break;
                    }
                }
                $this->connect->query("UPDATE homestead.Users SET `login` = '{$user->getLogin()}', `email` = '{$user->getEmail()}', `descr` = '{$user->getDesc()}' WHERE ID = {$editID}");
                break;
            case "array":
                $arr = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['users']));
                asort($arr);
                $fileEdit = null;
                for ($j = 0; $j < count($arr); $j++) {
                    if ($j === $user->getIndex()) {
                        $fileEdit = $arr[$j];
                        break;
                    }
                }

                $file = __DIR__ . $this->connect['file']['users'] . $fileEdit;
                $el = $this->readFile($file);

                $el['login'] = $user->getLogin();
                $el['email'] = $user->getEmail();
                $el['descr'] = $user->getDesc();

                file_put_contents(__DIR__ . $this->connect['file']['users'] . $fileEdit, '');
                file_put_contents(__DIR__ . $this->connect['file']['users'] . $fileEdit, json_encode($el));
                break;
        }
    }

    /**
     * Отображение всех пользователей, которые представлены в базе данных
     **/
    public function getAllUsers(): array
    {
        $usersNameArr = array();
        switch (gettype($this->connect)) {
            case "object":
                $query = "SELECT * FROM homestead.Users";
                $result = $this->connect->query($query);
                while ($row = $result->fetch_assoc()) {
                    array_push($usersNameArr, $row['login']);
                }
                return $usersNameArr;
            case "array":
                $arr1 = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['users']));
                foreach ($arr1 as $value) {
                    $file = __DIR__ . $this->connect['file']['users'] . $value;
                    $data = $this->readFile($file);
                    $user = new User($data);
                    array_push($usersNameArr, $user->getLogin());
                }
                return $usersNameArr;
        }
        return $usersNameArr;
    }

    /**
     * Авторизция пользователя с проверкой существует ли такой пользователя
     * Если он существует добавляем ему роль в сессию  для дальнейшей проверки прав доступа
     * Если такого пользователя нету, выход из авторизации
     **/
    public function getAuthorization(User $user): ?string
    {
        switch (gettype($this->connect)) {
            case "object":
                $query = "SELECT * FROM homestead.Users WHERE email = '{$user->getLogin()}' AND pass = '{$user->getPass()}'";
                $result = $this->connect->query($query)->fetch_assoc();
                if ($result['role'] === $this->roles::ADMIN_ROLE) {
                    $_SESSION['ROLE'] = $this->roles::ADMIN_ROLE;
                    $_SESSION['NAME'] = $result['login'];
                    $_SESSION['id'] = (int)$result['ID'];
                    return $user->getLogin();
                }
                $_SESSION['ROLE'] = $this->roles::USER_ROLE;
                $_SESSION['NAME'] = $result['login'];
                $_SESSION['id'] = (int)$result['ID'];
                return $user->getLogin();
            case "array":
                $arr = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['users']));
                foreach ($arr as $file) {
                    $el = $this->readFile(__DIR__ . $this->connect['file']['users'] . $file);
                    if ($el['email'] === $user->getLogin() && $el['pass'] === $user->getPass()) {
                        if ($el['role'] == $this->roles::ADMIN_ROLE) {
                            $_SESSION['ROLE'] = $this->roles::ADMIN_ROLE;
                            $_SESSION['NAME'] = $el['login'];
                            $_SESSION['id'] = $file;
                            return $user->getLogin();
                        }
                        $_SESSION['ROLE'] = $this->roles::USER_ROLE;
                        $_SESSION['NAME'] = $el['login'];
                        $_SESSION['id'] = $file;
                        return $user->getLogin();
                    }
                }
        }

        return null;
    }
}