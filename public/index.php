<?php
session_start();

require __DIR__ . "/../core/Autoloader.php";

use core\Router;

new Router();

//Router::getInstance();