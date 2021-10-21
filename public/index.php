<?php
session_start();

require __DIR__ . "/../core/Autoloader.php";

use core\Router;

Router::getInstance();