<?php

function createUsersTable()
{
    $data = require_once __DIR__ . "/../../public/config/mysql_config/config_database.php";
    $mysql = mysqli_connect('192.168.10.10',$data['base']['username'],$data['base']['password'],$data['base']['database']);
    $query = "CREATE TABLE `homestead`.`Users` (
              `ID` INT NOT NULL AUTO_INCREMENT,
              `login` VARCHAR(45) NULL,
              `email` VARCHAR(45) NULL,
              `pass` VARCHAR(45) NULL,
              `data` VARCHAR(45) NULL,
              `descr` VARCHAR(100) NULL,
              `role` VARCHAR(100) NULL,
              
              PRIMARY KEY (`ID`),
              UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE)";
    $mysql->query($query);
}

createUsersTable();