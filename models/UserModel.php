<?php

namespace models;

use core\Model;
use core\Helper;
use entites\User;

class UserModel extends Model
{
    public string $directory;
    public Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/Users/";
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
        $arrayFiles = $this->helper->myscandir($this->directory);
        asort($arrayFiles);
        foreach ($arrayFiles as $file) {
            $fileName = $this->directory . $file;
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
    public function getAllUsers(): ?array
    {
        $arr1 = array_values($this->helper->myscandir($this->directory));
        $usersNameArr = array();

        foreach ($arr1 as $value) {
            $file = $this->directory . $value;
            $data = $this->readFile($file);
            $user = new User($data);
            array_push($usersNameArr, $user->getLogin());
        }
        return $usersNameArr;
    }

}