<?php

namespace models\sqlModels;

use core\Model;
use entites\User;
use enums\Roles;
use enums\TypeConnect;
use interfaces\Users;

class SqlUserModel extends Model implements Users
{
    protected static ?SqlUserModel $instance = null;

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
    public static function getInstance(): SqlUserModel
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
        $userData = array(
            'login' => $user->getLogin(),
            'email' => $user->getEmail(),
            'pass' => $user->getPass(),
            'data' => $user->getData(),
            'descr' => $user->getDesc(),
            'role' => 'user',
            'id' => 1,
        );

        $arrOfColumns = [];

        $query = "INSERT INTO homestead.users 
                          SET `id` = null,";

        $result = $this->connect->query(file_get_contents(__DIR__ . "/../../config/sql/Users/columnsUsers.sql"));

        while ($columnName = $result->fetch_assoc()) {
            array_push($arrOfColumns, $columnName);
        }

        $arrOfColumns = array_column($arrOfColumns, "COLUMN_NAME");

        $arr = array_intersect(array_keys($userData), $arrOfColumns);

        foreach ($arr as $el) {
            if ($el === 'id') {
                continue;
            }
            $query .= "`{$el}` = " . "'{$userData[$el]}'" . "," . "\n";
        }

        $query = substr($query, 0, -2);

        $this->connect->query($query);
        return $user->getLogin();
    }

    /**
     * Удаление пользователя из базы данных, удаление соответсвующего файла
     * Удаление блока с пользоватлем происходит на frontend
     **/
    public function deleteUser(int $indexDel, int $id)
    {
        $this->connect->query("DELETE FROM homestead.users WHERE id = $id");
    }

    /**
     * Редактирование пользователя по которому произошло событие нажатия
     * Обрабатывается по уникальному индексу этого пользователя
     * Получаем новые данные о пользователе и менем их в его файле
     **/
    public function editUser(User $user)
    {
        $this->connect->query("UPDATE homestead.users SET `login` = '{$user->getLogin()}', `email` = '{$user->getEmail()}', `descr` = '{$user->getDesc()}' WHERE id = {$user->getIndex()}");
    }

    /**
     * Отображение всех пользователей, которые представлены в базе данных
     **/
    public function getAllUsers(): array
    {
        $usersNameArr = array();
        $result = $this->connect->query(file_get_contents(__DIR__ . "/../../config/sql/allUsers.sql"));
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push($usersNameArr, $row);
            }
        }
        return $usersNameArr;
    }

    /**
     * Авторизция пользователя с проверкой существует ли такой пользователя
     * Если он существует добавляем ему роль в сессию  для дальнейшей проверки прав доступа
     * Если такого пользователя нету, выход из авторизации
     */
    public function getAuthorization(User $user): array
    {
        $query = "SELECT * FROM homestead.users WHERE email = '{$user->getLogin()}' AND pass = '{$user->getPass()}'";
        $result = $this->connect->query($query)->fetch_assoc();
        if (!empty($result)) {
            if ($result['role'] === Roles::ADMIN_ROLE) {
                return ['ROLE' => Roles::ADMIN_ROLE, 'NAME' => $result['login'], 'id' => (int)$result['id']];
            }
            return ['ROLE' => Roles::USER_ROLE, 'NAME' => $result['login'], 'id' => (int)$result['id']];
        }
        return [];
    }
}