<?php

namespace core;

class Helper
{

    /**
     * @param string $dir
     * @param int $sort
     * @return array|null
     * Сканирование папки с базой данных для
     * получения оттуда списка файлов с пользователями для последующей работы с ними
     **/
    public function myscandir(string $dir, int $sort = 0): array
    {
        $list = scandir($dir, $sort);

        if (!$list) {
            return $list;
        }

        if ($sort === 0) {
            unset($list[0], $list[1]);
        }
        return $list;
    }

    /**
     * @return array|null
     * Функция для обработки HTTP запросов, которые php не может просто так обработать
     * Это для всех запросов, кроме GET, POST
     * Для PUT, DELETE ...
     * Основано на обработке супер глобального файла "php://input" в котором харнятся все данные
     **/
    public function resetAPI(): ?array
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case "DELETE":
                $arr = array();
                $content = file_get_contents("php://input");
                $item = explode('=', $content);
                foreach ($item as $key => $value) {
                    if ($key % 2 === 0) {
                        $v = $item[$key];
                        $arr[$v] = $item[$key + 1];
                    }
                }
                return $arr;
            default:
                return null;
        }
    }
}