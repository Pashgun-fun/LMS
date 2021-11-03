<?php

namespace models;

use core\Model;
use entites\User;
use enums\Roles;
use enums\TypeConnect;

class UserModel extends Model
{

    protected static ?UserModel $instance = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Закрываем подключение к БД
     */
    public function __destruct()
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
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
            case TypeConnect::OBJECT_CONNECT:
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
            case TypeConnect::ARRAY_CONNECT:
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
                    'descr' => $user->getDesc(),
                    'data' => $user->getData(),
                    'pass' => $user->getPass(),
                    'id' => 1
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
    public function deleteUser(int $indexDel, int $id)
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                $this->connect->query("DELETE FROM homestead.Users WHERE id = $id");
                break;
            case TypeConnect::ARRAY_CONNECT:
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
            case TypeConnect::OBJECT_CONNECT:
                $this->connect->query("UPDATE homestead.Users SET `login` = '{$user->getLogin()}', `email` = '{$user->getEmail()}', `descr` = '{$user->getDesc()}' WHERE id = {$user->getIndex()}");
                break;
            case TypeConnect::ARRAY_CONNECT:
                $arr = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['users']));
                asort($arr);
                $fileEdit = null;
                for ($j = 0; $j < count($arr); $j++) {
                    if ($j === $user->getIndexEdit()) {
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
            case TypeConnect::OBJECT_CONNECT:
                $result = $this->connect->query(file_get_contents(__DIR__ . "/../config/sql/allUsers.sql"));
                while ($row = $result->fetch_assoc()) {
                    array_push($usersNameArr, $row);
                }
                return $usersNameArr;
            case TypeConnect::ARRAY_CONNECT:
                $arr1 = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['users']));
                foreach ($arr1 as $value) {
                    $file = __DIR__ . $this->connect['file']['users'] . $value;
                    $data = $this->readFile($file);
                    $user = new User($data);
                    array_push($usersNameArr, $data);
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
    public function getAuthorization(User $user): array
    {
        switch (gettype($this->connect)) {
            case TypeConnect::OBJECT_CONNECT:
                $query = "SELECT * FROM homestead.Users WHERE email = '{$user->getLogin()}' AND pass = '{$user->getPass()}'";
                $result = $this->connect->query($query)->fetch_assoc();
                if ($result['role'] === Roles::ADMIN_ROLE) {
                    return ['ROLE' => Roles::ADMIN_ROLE, 'NAME' => $result['login'], 'id' => (int)$result['id']];
                }
                return ['ROLE' => Roles::USER_ROLE, 'NAME' => $result['login'], 'id' => (int)$result['id']];
            case TypeConnect::ARRAY_CONNECT:
                $arr = array_values($this->helper->myscandir(__DIR__ . $this->connect['file']['users']));
                foreach ($arr as $file) {
                    $el = $this->readFile(__DIR__ . $this->connect['file']['users'] . $file);
                    if ($el['email'] === $user->getLogin() && $el['pass'] === $user->getPass()) {
                        if ($el['role'] == Roles::ADMIN_ROLE) {
                            return ['ROLE' => Roles::ADMIN_ROLE, 'NAME' => $el['login'], 'id' => (int)$file];
                        }
                        return ['ROLE' => Roles::USER_ROLE, 'NAME' => $el['login'], 'id' => (int)$file];
                    }
                }
                break;
        }
        return [];
    }
}