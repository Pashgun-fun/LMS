<?php

namespace models;

use core\Model;

class PageModel extends Model
{
    public $directory = null;

    public function __construct()
    {
        $this->directory = __DIR__ . "/../database/";
    }

    public function openEditWindow(int $indexEdit): ?array
    {
        $file = $this->directory . $indexEdit . '.txt';
        return $this->readFile($file);
    }
}