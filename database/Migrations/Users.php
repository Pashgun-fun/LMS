<?php

function createUsersTable()
{
    $data = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
    $mysql = mysqli_connect('192.168.10.10',$data['base']['username'],$data['base']['password'],$data['base']['database']);
    $query = "CREATE TABLE `homestead`.`Users` (
              `ID` INT NOT NULL AUTO_INCREMENT,
              `Login` VARCHAR(45) NULL,
              `Email` VARCHAR(45) NULL,
              `Password` VARCHAR(45) NULL,
              `Date` VARCHAR(45) NULL,
              `Description` VARCHAR(100) NULL,
              PRIMARY KEY (`ID`),
              UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE)";
    $mysql->query($query);
}

createUsersTable();