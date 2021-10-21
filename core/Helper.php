<?php

namespace core;

class Helper
{

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