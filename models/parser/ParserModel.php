<?php

namespace models\parser;

use http\Env\Request;
use mysqli;

class ParserModel
{
    public array $dataArray;
    public \mysqli $mysqli;
    public array $arrOfCategories = [
        "subchapter" => [],
        "brend" => [],
        "model" => [],
        "color" => [],
        "chapter" => [],
    ];

    function __construct(array $arr)
    {
        $this->dataArray = $arr;
        $this->mysqli = new mysqli(
            "192.168.10.10",
            "homestead",
            "secret",
            "homestead"
        );
    }

    function __destruct()
    {
        $this->mysqli->close();
    }

    /**
     * Заполнение таблиц категорий по запросам в БД
     */
    public function createTableCategories(array $arr, string $str)
    {
        switch ($str) {
            case "brend":
                foreach ($arr["brend"] as $key => $value) {
                    if ($value !== null) {
                        $query = "INSERT INTO homestead.brend SET `id` = null, `type` = '{$value}'";
                        $this->mysqli->query($query);
                    }
                }
                break;
            case "model":
                foreach ($arr["model"] as $key => $value) {
                    if ($value !== null && $value !== "") {
                        $query = "INSERT INTO homestead.model SET `id` = null, `type` = '{$value}'";
                        $this->mysqli->query($query);
                    }
                }
                break;
            case "subchapter":
                foreach ($arr["subchapter"] as $key => $value) {
                    if ($value !== null && $value !== "") {
                        $query = "INSERT INTO homestead.subchapter SET `id` = null, `type` = '{$value}'";
                        $this->mysqli->query($query);
                    }
                }
                break;
            case "color":
                foreach ($arr["color"] as $key => $value) {
                    if ($value !== null) {
                        $query = "INSERT INTO homestead.color SET `id` = null , `type` = '{$value}'";
                        $this->mysqli->query($query);
                    }
                }
                break;
            case "chapter":
                foreach ($arr["chapter"] as $key => $value) {
                    if ($value !== null) {
                        $query = "INSERT INTO homestead.chapter SET `id` = null , `type` = '{$value}'";
                        $this->mysqli->query($query);
                    }
                }
                break;
        }
    }

    /**
     * Создание массива с категориями для дальнейшего занесения в БД
     */
    public function createCategories()
    {
        foreach ($this->dataArray as $key => $value) {
            foreach ($value as $index => $item) {
                switch ($index) {
                    case "color":
                        array_push($this->arrOfCategories["color"], $item);
                        break;
                    case "subchapter":
                        array_push($this->arrOfCategories["subchapter"], $item);
                        break;
                    case "brend":
                        array_push($this->arrOfCategories["brend"], $item);
                        break;
                    case "model":
                        array_push($this->arrOfCategories["model"], $item);
                        break;
                    case "chapter":
                        array_push($this->arrOfCategories["chapter"], $item);
                        break;
                }
            }
        }

        /**
         * Убираем повторяющиеся значения в массиве
         */
        $this->arrOfCategories["brend"] = array_unique($this->arrOfCategories["brend"]);
        $this->arrOfCategories["model"] = array_unique($this->arrOfCategories["model"]);
        $this->arrOfCategories["color"] = array_unique($this->arrOfCategories["color"]);
        $this->arrOfCategories["chapter"] = array_unique($this->arrOfCategories["chapter"]);
        $this->arrOfCategories["subchapter"] = array_diff(array_unique($this->arrOfCategories["subchapter"]), $this->arrOfCategories["color"]);
        array_push($this->arrOfCategories["subchapter"], "н/д");

        /**
         * Вызов функций для заполнения таблиц в БД
         * Если таких таблиц нету, запустите Migrations.php
         */
        $this->createTableCategories($this->arrOfCategories, "brend");
        $this->createTableCategories($this->arrOfCategories, "model");
        $this->createTableCategories($this->arrOfCategories, "color");
        $this->createTableCategories($this->arrOfCategories, "chapter");
        $this->createTableCategories($this->arrOfCategories, "subchapter");

    }

    /**
     * Функция собирающая в себе всё вместе для занесения в одну общую базу товаров
     */
    public function sendToBase()
    {
        $this->createCategories();
        $this->mysqli->begin_transaction();
        foreach ($this->dataArray as $key => $value) {
            $queryForAll = "INSERT INTO homestead.allThings SET `id` = null, ";
            foreach ($value as $index => $item) {
                switch ($index) {
                    case "chapter":
                        $item = $this->mysqli->query("SELECT `id` FROM homestead.chapter WHERE `type` = '{$item}'")->fetch_assoc()['id'];
                        break;
                    case "brend":
                        if ($this->mysqli->query("SELECT `id` FROM homestead.brend WHERE `type` = '{$item}'")->fetch_assoc() !== null) {
                            $item = ($this->mysqli->query("SELECT `id` FROM homestead.brend WHERE `type` = '{$item}'")->fetch_assoc())['id'];
                        }
                        break;
                    case "color":
                        if ($this->mysqli->query("SELECT `id` FROM homestead.color WHERE `type` = '{$item}'")->fetch_assoc() !== null) {
                            $item = ($this->mysqli->query("SELECT `id` FROM homestead.color WHERE `type` = '{$item}'")->fetch_assoc())['id'];
                        }
                        break;
                    case "model":
                        if ($this->mysqli->query("SELECT `id` FROM homestead.model WHERE `type` = '{$item}'")->fetch_assoc() !== null) {
                            $item = ($this->mysqli->query("SELECT `id` FROM homestead.model WHERE `type` = '{$item}'")->fetch_assoc())['id'];
                        }
                        break;
                    case "subchapter":
                        if ($this->mysqli->query("SELECT `id` FROM homestead.subchapter WHERE `type` = '{$item}'")->fetch_assoc() !== null) {
                            $item = ($this->mysqli->query("SELECT `id` FROM homestead.subchapter WHERE `type` = '{$item}'")->fetch_assoc())['id'];
                        }
                        break;
                }
                $queryForAll .= "`{$index}` = " . "'{$item}'" . "," . "\n";
            }
            $queryForAll = substr($queryForAll, 0, -2);
            $i = 0;
            while ($i < 160) {
                $this->mysqli->query($queryForAll);
                $i++;
            }
        }
        $this->mysqli->commit();
    }
}