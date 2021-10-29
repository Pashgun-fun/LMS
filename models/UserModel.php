<?php

namespace models;

use core\Model;
use core\Helper;
use entites\User;
use core\mysql\Variability;

class UserModel extends Model
{
    public string $directory;


    public Helper $helper;
    public Variability $variability;
    public array $variant;
    protected static ?UserModel $instance = null;
    protected $connect = null;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->variability = new Variability();
        $this->directory = __DIR__ . "/../database/Users/";
        $this->connect = $this->variability->chooseVariant();
    }

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
                                    '{$user->getDesc()}')";
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

                $newFile = $this->directory . (+array_pop($arrayFiles) + 1);
                $this->writeFile($newFile, $userData);
                return $user->getLogin();
        }
        return "";
    }

    /**
     * Удаление пользователя из базы данных, удаление соответсвующего файла
     * Удаление блока с пользоватлем происходит на frontend
     **/
    public function deleteUser(int $indexDel)
    {
        $this->delete($this->directory, $indexDel);
    }

    /**
     * Редактирование пользователя по которому произошло событие нажатия
     * Обрабатывается по уникальному индексу этого пользователя
     * Получаем новые данные о пользователе и менем их в его файле
     **/
    public function editUser(User $user)
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        asort($arr);
        $fileEdit = null;
        for ($j = 0; $j < count($arr); $j++) {
            if ($j === $user->getIndex()) {
                $fileEdit = $arr[$j];
                break;
            }
        }

        $file = $this->directory . $fileEdit;
        $el = $this->readFile($file);

        $el['login'] = $user->getLogin();
        $el['email'] = $user->getEmail();
        $el['desc'] = $user->getDesc();

        file_put_contents($this->directory . $fileEdit, '');
        file_put_contents($this->directory . $fileEdit, json_encode($el));
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
                    array_push($usersNameArr, $row['Login']);
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
}