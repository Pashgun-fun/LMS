<?php

namespace models;

use core\Helper;
use core\Model;


class UserModel extends Model
{
    public string $directory;
    protected Helper $helper;

    public function __construct()
    {
        $this->helper = new Helper();
        $this->directory = __DIR__ . "/../database/";
    }

    public function newUser(User $user): string
    {
        $arrayFiles = array_values($this->helper->myscandir($this->directory));
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

        $newFile = $this->directory . count($arrayFiles) . '.txt';
        $this->writeFile($newFile, $userData);

        return $user->getLogin();
    }

    public function deleteUser(int $indexDel)
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        unlink($this->directory . $arr[$indexDel]);
    }

    public function editUser(User $user)
    {
        $file = $this->directory . $user->getIndex() . '.txt';
        $el = $this->readFile($file);

        $el['login'] = $user->getLogin();
        $el['email'] = $user->getEmail();
        $el['desc'] = $user->getDesc();

        file_put_contents($this->directory . $user->getIndex() . '.txt', '');
        file_put_contents($this->directory . $user->getIndex() . '.txt', json_encode($el));
    }

    public function sortUsers()
    {
        $arr = array_values($this->helper->myscandir($this->directory));
        for ($j = 0; $j < array_values($arr); $j++) {
            rename($this->directory . $arr[$j], $this->directory . $j . ".txt");
        }
    }

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