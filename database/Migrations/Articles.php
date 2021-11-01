<?php

function createArticlesTable()
{
    $data = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
    $mysql = mysqli_connect('192.168.10.10', $data['base']['username'], $data['base']['password'], $data['base']['database']);
    $query = "CREATE TABLE `homestead`.`Articles` (
              `ID` INT NOT NULL AUTO_INCREMENT,
              `UserID` INT NULL,
              `title` VARCHAR(45) NULL,
              `text` TEXT NULL,
              `date` VARCHAR(100) NULL,
              PRIMARY KEY (`ID`),
              UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE)";
    $mysql->query($query);
}

createArticlesTable();