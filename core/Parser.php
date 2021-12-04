<?php

namespace core;

require_once __DIR__ . "/../core/Autoloader.php";

use models\parser\ParserModel;

class Parser
{
    public ParserModel $parserModel;
    public array $config;
    public int $key1 = 0;
    public array $database = [];
    public array $databaseInner = [
        "chapter" => '',
        "subchapter" => '',
        "articul" => '',
        "brend" => '',
        "model" => '',
        "namespace" => '',
        "size" => '',
        "color" => '',
    ];

    function __construct()
    {
        $this->config = require_once __DIR__ . "/../config/parser/parser.php";
    }

    /**
     * Т.к. проверка на подраздел осуществяется по окончанияем, проверка, чтобы подразделы не пересекались с цветами
     */
    public function checkTrueSubtitle(string $str): string
    {
        foreach ($this->config['color'] as $key => $value) {
            if ($value === $str) {
                return "";
            }
        }
        return $str;
    }

    /**
     * Функция для определения параметров размера
     * необходима для определния подходит ли параметр под числовой размер
     */
    public function checkParam(int $int, int $border1, int $border2): string
    {
        if ($int >= $border1 && $int <= $border2) {
            return (string)$int;
        }
        return "Не указано";
    }

    /**
     * Функция для смены элементов в массиве метами по ключам
     */
    public function arraySwap(int $key1, int $key2, array &$array): bool
    {
        if (isset($array[$key1]) && isset($array[$key2])) {
            list($array[$key1], $array[$key2]) = array($array[$key2], $array[$key1]);
            return true;
        }
        return false;
    }

    /**
     * Удаление последнего символа переноса строки в местах, где она появляется
     * при разбиении строки функцией explode
     */
    public function removeNewLine(string $str): string
    {
        if ($str[strlen($str) - 1] == "\n") {
            return trim($str, "\n");
        }
        return $str;
    }

    /**
     * Проверка раздела на соотвествие с конфигом, в котором занесены основные разделы товаров
     */
    public function checkChapterAndNamespace(string $info)
    {
        $arrOfWords = [];
        array_push($arrOfWords, explode(" ", $info));
        foreach (array_shift($arrOfWords) as $key => $values) {
            foreach ($this->config['captions'] as $index => $data) {

                /**
                 * После разиении строки сравниваем каждый элемент с тем, что у нас в конфиге по разделам
                 * и если совпадает, то заносим в ассоциатнвный массив для для данного товара
                 */
                if ($values === $data) {
                    $this->databaseInner['chapter'] = $values;
                    $this->databaseInner['namespace'] = $this->databaseInner['chapter'];
                }
            }
        }
        $this->databaseInner['orientation'] = "н/д";
    }

    /**
     * Проверка артикула для товара, есть ли такой вообще, если существует присваиваем по своему ключу в массив
     * Проверка идёт по регулярным выражениям после разбиения приходящей строки на отдельные слова и сравнивания их с
     * регулярным выражением
     */
    public function checkArticle(string $str)
    {
        $arrOfWords = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach (array_shift($arrOfWords) as $key => $values) {
            $newLine = $this->removeNewLine($values);

            /**
             * Строка должна быть не пустая и не менее 7 символов
             * Может быть артикул в скобочках, поэтоу проверяем, первый и последний символ на скобочки
             * Артикул может состоять только из из цифр или только из латинских букв
             * Также может состоять как из цифр, так и из букв
             */
            if (preg_match("~[^[a-zа-яё]+\d+$]|[0-9]{5,15}~", $newLine)) {
                $this->databaseInner['articul'] = $newLine;
                return;
            }
        }
        $this->databaseInner['articul'] = 'н/д';
    }

    /**
     * Функция для определения подраздела определнного товара
     */
    public function checkSubtitle(string $str)
    {
        $this->databaseInner['subchapter'] = "н/д";
        $arrOfWords = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach (array_shift($arrOfWords) as $key => $values) {
            $i = 0;
            while ($i < count($this->config['endings'])) {
                $j = 0;
                /**
                 * Проверяем слова содержащие как минимум 5 символов
                 * Далее берем последние 2 символа строки и сравниваем их с конфигом окончаний для подраздела
                 * Если есть совпадение, то этот элемент и будет подразделом и заносим в массив
                 */
                if (strlen($values) >= 12 &&
                    substr($values, -4) == $this->config['endings'][$i] &&
                    !strpos($values, "-")) {
                    $this->databaseInner['subchapter'] = $this->checkTrueSubtitle($values);
                    return;
                }
                $i++;
            }
        }
    }

    /**
     * Функция пердназначенная для определения хвата
     * Используется только в том случае, если радзел товара является клюшка
     * Могут быть варианты только с l - левый и r - правый
     */
    public function checkOrientation(string $str)
    {
        $arrOfWords = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach (array_shift($arrOfWords) as $key => $values) {
            $newLine = $this->removeNewLine($values);
            if ($newLine === 'l' || $newLine === 'r') {
                $this->databaseInner['orientation'] = $newLine;
                return;
            }
        }
        $this->databaseInner['orientation'] = "н/д";
    }

    /**
     * Функция предназначена толко для определения цвета товара, если он указан
     */
    public function checkColor(string $str)
    {
        $arrOfWords = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach ($arrOfWords[0] as $key => $value) {
            $newLine = $this->removeNewLine($value);

            /**
             * Обрабатывает случай, когда цвета представлены разделителем "/"
             * Из этой строки разбиваем массив и если элементы массива не кирилица удаляем это слово
             * Иначе соединяем цвета, которые остались и присваиваем и удаляем пустые элементы массива
             */
            if (strpos($newLine, "/")) {
                $newArr = explode("/", $newLine);
                foreach ($newArr as $i => $item) {
                    if (preg_match("/[^a-zа-яё ]/iu", $item)) {
                        unset($newArr[$i]);
                    }
                }
                $newArr = implode(",", $newArr);
                if (!empty($newArr)) {
                    $this->databaseInner['color'] = $newArr;
                    return;
                }
            }

            /**
             * Срабатывает если цвет разделен точкой или тире
             * Только русские буквы и содержит точку и не содержит скобочек и длинна от 3 до 20 символов
             */
            if (preg_match("~[^a-zа-яё]+[.]+[^(?:(?!)).)+$]{3,20}+$~", $newLine)) {
                $this->databaseInner['color'] = $newLine;
                return;
            }

            /**
             * Разбор цвета, если тот заключен в скобочки
             * Если длина строки соответсвует необходимому числу, для определения цвета
             * Сравниваем ее на кирилицу во всем промеутке между скобочками
             */
            if (strlen($newLine) > 9 &&
                preg_match("/^[а-яё -]/i", substr($newLine, 1, -1)) &&
                $newLine[0] === "(" &&
                $newLine[strlen($newLine) - 1] = ")") {
                $this->databaseInner['color'] = $newLine;
                return;
            }
        }

        /**
         * Если последнее слово в сторке русское проверяем его на принадлдежность цвету
         */
        if (end($arrOfWords[0]) &&
            preg_match("/^[а-яё -]+$/i", $this->removeNewLine(end($arrOfWords[0])))) {
            $this->databaseInner['color'] = $this->removeNewLine(end($arrOfWords[0]));
            return;
        }

        $this->databaseInner['color'] = "н/д";

    }

    /**
     * Проверка размера на соответствие данным из конфига
     * Для всего кроме клюшек
     */
    public function checkSize(string $str)
    {
        $arrOfWords = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach (array_shift($arrOfWords) as $key => $value) {
            $newLine = $this->removeNewLine($value);
            foreach ($this->config['size'] as $i => $item) {
                if ($newLine === $item) {
                    $this->databaseInner['size'] = $item;
                    return;
                }
            }
        }
        $this->databaseInner['size'] = "н/д";
    }

    /**
     * Функция для проверки размера клюшки
     */
    public function checkSizeSpecial(string $str)
    {
        $arrOfWords = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach (array_shift($arrOfWords) as $key => $value) {
            $newLine = $this->removeNewLine($value);

            /**
             * Если строка содержит разделитель размера "-" то проверяем на длинну и присваиваем как размер клюшки
             */
            if (strpos($newLine, "-") && strlen($newLine) <= 7) {
                $this->databaseInner['size'] = $newLine;
                return;
            }

            /**
             * Проверка размера клюшки если параметр является просто числом
             * Проверяем его на дпустимыый диапазон функцией checkParam
             */
            if (ctype_digit($newLine)) {
                $size = (int)$newLine;
                $this->databaseInner['size'] = $this->checkParam($size, 1, 200);
                return;
            }
        }
        $this->databaseInner['size'] = "н/д";
    }

    /**
     * Функция на проверку бренда и модели
     */
    public function checkBrandAndModel(string $str)
    {
        $this->databaseInner['brend'] = "н/д";
        $this->databaseInner['model'] = "н/д";
        $arrOfWords = [];
        $arr = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach (array_shift($arrOfWords) as $key => $value) {
            $newLine = $this->removeNewLine($value);
            /**
             * Выборка всех слов состоящих только из латинских букв в диапазоне длинн стрроки от 3 до 25
             * И добавление в новый массив
             */
            if (preg_match("/^[a-zA-Z]{3,25}+$/", $newLine)) {
                array_push($arr, $newLine);
            }
        }

        /**
         * Добавление в массив товара в соотствующие поля значение
         * Если это бренд, то будет добавлено первое слово
         * Если это модель, то набор всех слов, кроме первого соединенных функцией implode
         */
        if (!empty($arr)) {
            $this->databaseInner['brend'] = array_shift($arr);
            if (count($arr) >= 1) {
                $this->databaseInner['model'] = implode(" ", array_slice($arr, 0));
            }
            return;
        }
    }

    /**
     * Функция для проверки размера коньков
     */
    public function checkSizeForSkates(string $str)
    {
        $arrOfWords = [];
        $arrOfSize = [];
        array_push($arrOfWords, explode(" ", $str));
        foreach ($arrOfWords[0] as $key => $value) {
            $newLine = $this->removeNewLine($value);

            /**
             * Нахождение размера, если он составлен в виде str(1):int()
             */
            if (preg_match("/[:]/", $newLine)) {
                $this->databaseInner['size'] = $newLine;
                return;
            }

            /**
             * Логика для нахождение размера, если не выполнилось ни одно из условий выше
             * Значит размер представлен просто цифрой в допустимом ему диапазоне
             * Размер имеет вид  int()|float()
             */
            if (preg_match("/^[0-9]{1,3}$/", $newLine)) {
                if ($this->checkParam((int)$newLine, 1, 48) !== "Не указано") {
                    $this->databaseInner['size'] = $newLine;
                    return;
                }
            }

            /**
             * Логика для парсинга размера типа str(2) - int()|float()
             */
            if (preg_match("/^[-+]?[0-9]*[.,]?[0-9]+(?:[eE][-+]?[0-9]+)?$/", $newLine) && $newLine[0] !== "-") {
                array_push($arrOfSize, $newLine);
            }
            foreach ($arrOfSize as $i => $item) {
                if ($this->checkParam((int)$item, 1, 48) !== "Не указано" && $key >= 2 && preg_match("/^[a-z]{1,3}$/i", $arrOfWords[0][$key - 2])) {
                    $this->databaseInner['size'] = $arrOfWords[0][$key - 2] . " " . $item;
                    return;
                }
            }
        }
        $this->databaseInner['size'] = "н/д";
    }

    /**
     * Общая функция объеденяющая в себе все парсеры для каждой составляющей
     */
    public function getInfo()
    {
        /**
         * Разбиваем пришедший файл на сторки и в каждой строке
         * Если присутствуют лишние символы и пробелы удаляем их и весь текст в строках приводим к нижнему регистру
         */
        $arr = explode(PHP_EOL, file_get_contents(__DIR__ . "/../files/test.csv"));
        for ($i = 0; $i < count($arr); $i++) {
            if (preg_match('~"~', $arr[$i])) {
                $arr[$i] = preg_replace('~"~', '', $arr[$i]);
            }
            $arr[$i] = preg_replace('/\s+/', ' ', $arr[$i]);
            $arr[$i] = trim($arr[$i], "");
            $arr[$i] = mb_strtolower($arr[$i]);
        }

        /**
         * Перебираем строки из и те, которые имеют совпадение отправляются на дальнейший парсинг
         */
        $i = 0;
        while ($i < count($arr)) {
            $j = 0;
            while ($j < count($this->config['captions'])) {
                if (strpos($arr[$i], $this->config['captions'][$j]) !== false) {

                    /**
                     * Обнуление массива каждый раз при переходе к новой строке
                     */
                    $this->databaseInner = [
                        "chapter" => null,
                        "subchapter" => null,
                        "articul" => null,
                        "brend" => null,
                        "model" => null,
                        "namespace" => null,
                        "size" => null,
                        "color" => null,
                        "orientation" => null,
                    ];

                    /**
                     * Строка которая нашла совпадение по слову из конфига и пойдёт на обработку
                     */
                    $info = $arr[$i] . PHP_EOL;
                    /**
                     * Функции для парсинга по различным категориям
                     */
                    $this->checkChapterAndNamespace($info);
                    $this->checkSubtitle($info);
                    $this->checkArticle($info);
                    $this->checkSize($info);
                    $this->checkColor($info);
                    $this->checkBrandAndModel($info);
                    $this->checkSizeForSkates($info);

                    /**
                     * Специальные функции только для парсинга строки с клюшкой
                     */
                    if ($this->databaseInner['chapter'] == "клюшка") {
                        $this->checkOrientation($info);
                        $this->checkSizeSpecial($info);
                    }

                    /**
                     * Добавление массива со значениями в общий массив
                     */
                    array_push($this->database, $this->databaseInner);
                    break;
                } else {
                    $j++;
                }
            }
            $i++;
        }
        $this->parserModel = new ParserModel($this->database);
        $this->parserModel->sendToBase();
    }

}

$parse = new Parser();
$parse->getInfo();