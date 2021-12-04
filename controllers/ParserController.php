<?php

namespace controllers;

use core\Controller;
use models\ProductsModel;

class ParserController extends Controller
{

    protected ProductsModel $productsModel;

    function __construct()
    {
        parent::__construct();
        $this->productsModel = ProductsModel::getInstance();
    }

    /**
     * Вывод всех данных о продуктах на сайт
     */
    public function getProducts()
    {
        $arrOfProducts = $this->productsModel->processingProducts();
        if (empty($arrOfProducts)) {
            $this->view->products("Продукты доступны только при подключении к БД");
            return;
        }
        $this->view->products("", $arrOfProducts);
    }

    /**
     * Поиск по отдельно заданному полю, категории
     */
    public function searchProduct()
    {
        $dataProducts = $this->productsModel->getSearchProducts($_POST['value']);
        if (!empty($dataProducts)) {
            $this->view->products("", $dataProducts);
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Поиск по отдельно заданному полю, подкатегория
     */
    public function searchSubchapterProduct()
    {
        $dataProducts = $this->productsModel->getSearchProductsSubchapter($_POST['value']);
        if (!empty($dataProducts)) {
            $this->view->products("", $dataProducts);
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Поиск по отдельно заданному полю, бренд
     */
    public function searchBrendProduct()
    {
        $dataProducts = $this->productsModel->getSearchProductsBrend($_POST['value']);
        if (!empty($dataProducts)) {
            $this->view->products("", $dataProducts);
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Поиск по отдельно заданному полю, бренд
     */
    public function searchSizeProduct()
    {
        $dataProducts = $this->productsModel->getSearchProductsSize($_POST['value']);
        if (!empty($dataProducts)) {
            $this->view->products("", $dataProducts);
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Поиск по отдельно заданному полю, модель
     */
    public function searchModelProduct()
    {
        $dataProducts = $this->productsModel->getSearchProductsModel($_POST['value']);
        if (!empty($dataProducts)) {
            $this->view->products("", $dataProducts);
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Поиск по отдельно заданному полю, цвету
     */
    public function searchColorProduct()
    {
        $dataProducts = $this->productsModel->getSearchProductsColor($_POST['value']);
        if (!empty($dataProducts)) {
            $this->view->products("", $dataProducts);
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Фильтрация по полю категории в порядке по алфавите
     */
    public function filterByChapterStraight()
    {
        $dataProduct = $this->productsModel->getFilterByChapterStraight($_POST['arr']);
        $this->view->products("", $dataProduct);
    }

    /**
     * Фильтрация по полю категории в порядке обратному алфавиту
     */
    public function filterByChapterBack()
    {
        $dataProduct = $this->productsModel->getFilterByChapterBack($_POST['arr']);
        $this->view->products("", $dataProduct);
    }

    /**
     * Поиск товара по всем данным введенным в поля поиска
     */
    public function searchAllProducts()
    {
        $dataProduct = $this->productsModel->getSearchAllProducts($_POST['arr']);
        if (!empty($dataProduct)) {
            $this->view->products("", $dataProduct);
            return;
        }
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Пагинация
     */
    public function pagination()
    {
        $dataProduct = $this->productsModel->pagination($_POST['page']);
        $this->view->products("", $dataProduct);
    }
}