<?php

namespace models;

use core\Model;

class ProductsModel extends Model
{

    public array $arr;
    public array $arrOfSearchProducts;
    public static ?ProductsModel $instance = null;

    function __construct()
    {
        $this->arr = [];
        $this->arrOfSearchProducts = [];
        parent::__construct();
    }

    /**
     * Singleton
     * Чтобы объект не создавалася несолько раз один и тот же
     * а использовался один и тот же, если он уже создан
     */
    public static function getInstance(): ProductsModel
    {
        if (self::$instance === null) {
            return new self();
        }
        return self::$instance;
    }

    /**
     * Функция обрпабатывающая запрос к БД и возвраающая массив товаров
     * Его передаем в контролле и далее во view
     */
    public function processingProducts(): array
    {
        $arrOfProducts = [];
        switch (gettype($this->connect)) {
            case "array":
                return $arrOfProducts;
            case "object":
                /**
                 * Подключаем файл с основным sql запросом
                 * Который в дальнейшем будет использоваться практически в каждом методе
                 */
                $result = $this->connect->query(file_get_contents(__DIR__ . "/../config/sql/parser.sql"));
                if ($result) {
                    while ($product = $result->fetch_assoc()) {
                        array_push($arrOfProducts, $product);
                    }
                    return $arrOfProducts;
                }
        }
        return [];
    }

    /**
     * Поиск товаров только по полю категории
     */
    public function getSearchProducts(string $str): array
    {
        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
        /**
         * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа chapter
         */
        $query = mb_substr($query, 0, -1) . " " . "WHERE homestead.chapter.`type` = '{$str}'";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Поиск товаров только по полю подкатегории
     */
    public function getSearchProductsSubchapter(string $str): array
    {
        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
        /**
         * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа subchapter
         */
        $query = mb_substr($query, 0, -1) . " " . "WHERE homestead.subchapter.`type` = '{$str}'";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Поиск товаров только по полю бренду
     */
    public function getSearchProductsBrend(string $str): array
    {
        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
        /**
         * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа brend
         */
        $query = mb_substr($query, 0, -1) . " " . "WHERE homestead.brend.`type` = '{$str}'";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Поиск товаров только по полю модели
     */
    public function getSearchProductsModel(string $str): array
    {
        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
        /**
         * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа model
         */
        $query = mb_substr($query, 0, -1) . " " . "WHERE homestead.model.`type` = '{$str}'";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Поиск товаров только по полю цвету
     */
    public function getSearchProductsColor(string $str): array
    {
        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
        /**
         * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа color
         */
        $query = mb_substr($query, 0, -1) . " " . "WHERE homestead.color.`type` = '{$str}'";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Фильтрация товара, в данном слчае по категории по алфавиту
     */
    public function getFilterByChapterStraight(string $arr): array
    {
        $queryAll = "";
        /**
         * Создаём ассоциативный массив полученных с front`енда данных
         * Далее перебираем массив и убираем из него пустые значения
         */
        $this->arrOfSearchProducts = json_decode($arr, true);

        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");

        foreach ($this->arrOfSearchProducts as $key => $value) {
            if (empty($value)) {
                unset($this->arrOfSearchProducts[$key]);
            }
        }

        if (!empty($this->arrOfSearchProducts)) {
            /**
             * Создание специфичного окончания для запроса
             */
            foreach ($this->arrOfSearchProducts as $index => $item) {
                $queryAll .= "homestead.{$index}.`type` = '{$item}' AND ";
            }

            $queryAll = mb_substr($queryAll, 0, -4);

            $query = mb_substr($query, 0, -1);

            $query .= " " . "WHERE" . " " . $queryAll;
            /**
             * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа chapter
             * И с помощбью функции ORDER BY с флагом ASC фильтруем
             */
            $query .= "ORDER BY homestead.chapter.`type` ASC";
            $result = $this->connect->query($query);
            while ($prod = $result->fetch_assoc()) {
                array_push($this->arr, $prod);
            }
            return $this->arr;
        }

        $query = mb_substr($query, 0, -1);
        $query .= " " . "ORDER BY homestead.chapter.`type` ASC";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Фильтрация товара, в данном слчае по категории по алфавиту в обратном порядке
     */
    public function getFilterByChapterBack(string $arr): array
    {

        $queryAll = "";

        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");

        /**
         * Создаём ассоциативный массив полученных с front`енда данных
         * Далее перебираем массив и убираем из него пустые значения
         */
        $this->arrOfSearchProducts = json_decode($arr, true);

        foreach ($this->arrOfSearchProducts as $key => $value) {
            if (empty($value)) {
                unset($this->arrOfSearchProducts[$key]);
            }
        }

        if (!empty($this->arrOfSearchProducts)) {
            /**
             * Создание специфичного окончания для запроса
             */
            foreach ($this->arrOfSearchProducts as $index => $item) {
                $queryAll .= "homestead.{$index}.`type` = '{$item}' AND ";
            }

            $queryAll = mb_substr($queryAll, 0, -4);

            $query = mb_substr($query, 0, -1);

            $query .= " " . "WHERE" . " " . $queryAll;
            /**
             * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа chapter
             * И с помощбью функции ORDER BY с флагом ASC фильтруем
             */
            $query .= "ORDER BY homestead.chapter.`type` DESC";
            $result = $this->connect->query($query);
            while ($prod = $result->fetch_assoc()) {
                array_push($this->arr, $prod);
            }
            return $this->arr;
        }

        $query = mb_substr($query, 0, -1);
        $query .= " " . "ORDER BY homestead.chapter.`type` DESC";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Поиск товаров по всем данным, которые введены в поля для поиска
     */
    public function getSearchAllProducts(string $arr): array
    {
        $queryAll = "";

        /**
         * Создаём ассоциативный массив полученных с front`енда данных
         * Далее перебираем массив и убираем из него пустые значения
         */
        $this->arrOfSearchProducts = json_decode($arr, true);

        foreach ($this->arrOfSearchProducts as $key => $value) {
            if (empty($value)) {
                unset($this->arrOfSearchProducts[$key]);
            }
        }

        if (!empty($this->arrOfSearchProducts)) {
            /**
             * Создание специфичного окончания для запроса
             */
            foreach ($this->arrOfSearchProducts as $index => $item) {
                $queryAll .= "homestead.{$index}.`type` = '{$item}' AND ";
            }

            /**
             * Формируем основную часть запроса
             */
            $queryAll = mb_substr($queryAll, 0, -4);

            $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
            $query = mb_substr($query, 0, -1) . " " . "WHERE" . " " . $queryAll;

            /**
             * Соединяем данные для вывода найденный информации
             * Обрабатываем её и передаем на контроллер
             */
            $result = $this->connect->query($query);
            while ($prod = $result->fetch_assoc()) {
                array_push($this->arr, $prod);
            }
            return $this->arr;
        }

        return $this->processingProducts();
    }


    public function getSearchProductsSize(string $str): array
    {
        /**
         * Подключаем файл с основным sql запросом
         */
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
        /**
         * Добавочный запрос к базе данных для выбора всех значений из необходимого поля, в данном случа model
         */
        $query = mb_substr($query, 0, -1) . " " . "WHERE size = '{$str}'";
        $result = $this->connect->query($query);
        while ($prod = $result->fetch_assoc()) {
            array_push($this->arr, $prod);
        }
        return $this->arr;
    }

    /**
     * Пагинация для товаров
     */
    public function pagination(int $page): array
    {
        $products = [];
        $numberStart = $page * 15 - 15;
        $query = file_get_contents(__DIR__ . "/../config/sql/parser.sql");
        $query = mb_substr($query, 0, -1) . " " . "limit {$numberStart} ,15";
        $result = $this->connect->query($query);
        if ($result) {
            while ($product = $result->fetch_assoc()) {
                array_push($products, $product);
            }
        }
        return $products;
    }
}