<?php

function createNewsTable()
{
    $data = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
    $mysql = mysqli_connect('192.168.10.10',$data['base']['username'],$data['base']['password'],$data['base']['database']);
    $query = "CREATE TABLE `homestead`.`News` (
              `ID` INT NOT NULL AUTO_INCREMENT,
              `UserID` INT NULL,
              `title` VARCHAR(45) NULL,
              `text` text NULL,
              `date` VARCHAR(45) NULL,
              `seconds` bigint NULL,
              PRIMARY KEY (`ID`),
              UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE)";
    $mysql->query($query);
}

createNewsTable();