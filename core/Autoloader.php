<?php

/**
 * Автоматическое подтягивание классов из общего корня
 **/
spl_autoload_register(function (string $class) {
    $class = str_replace('\\', '/', $class);
    $path = __DIR__ . "/../" . $class . '.php';
    if (is_readable($path)) {
        require $path;
    }
});