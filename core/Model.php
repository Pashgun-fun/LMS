<?php

namespace core;

class Model
{
    protected function readFile(string $file): array
    {
        $db = fopen($file, 'a+');
        $read = trim(fread($db, filesize($file)));
        $el = json_decode($read, true);
        return $el;
    }

    protected function writeFile(string $newFile, array $userData)
    {
        $db = fopen($newFile, 'a+');
        $str = json_encode($userData);
        fwrite($db, $str);
    }
}