<?php

require_once('../config.php');

        // Create connection
        $conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname);
        // Check connection
        if ($conn->connect_error) {
            die("MySQL Verbindung fehlgeschlagen: " . $conn->connect_error);
        }
        
        //Create table
        $table = "CREATE TABLE warnsystem (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) NOT NULL,
        gender VARCHAR(30) NOT NULL,
        email VARCHAR(30) NOT NULL,
        phone BIGINT(20) NOT NULL,
        rand_id VARCHAR(20) NOT NULL,
        terms VARCHAR(10),
        verified INT(1),
        reg_date TIMESTAMP
        )";
        
        if ($conn->query($table) === TRUE) {
            echo '<p><strong>MySQL Tabelle warnsystem erfolgreich eingerichtet!</strong></p>';
        } else {
            echo "<p>Fehler: " . $table . "<br>" . $conn->error . "</p>";
        }
      
      
        
                //Create table
        $table = "CREATE  TABLE `steyreggbot` (
        `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `chatid` VARCHAR(45) NOT NULL ,
        `firstname` VARCHAR(45) NULL ,
        `lastname` VARCHAR(45) NULL ,
        `senspers` INT NULL ,
        `reg_date` TIMESTAMP
         )";
        
        if ($conn->query($table) === TRUE) {
            echo '<p><strong>MySQL Tabelle steyreggbot erfolgreich eingerichtet!</strong></p>';
        } else {
            echo "<p>Fehler: " . $table . "<br>" . $conn->error . "</p>";
        }
                
        $table = "CREATE TABLE statuslog (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        alertlevel INT(3) NOT NULL,
        alertindex INT(5) NOT NULL,
        alertmessage INT(3) NULL,
        warningtime VARCHAR(30) NULL
        )";
        
        if ($conn->query($table) === TRUE) {
                    echo '<p><strong>MySQL Tabelle statuslog erfolgreich eingerichtet!</strong></p>';
        } else {
                    echo "<p>Fehler: " . $table . "<br>" . $conn->error . "</p>";
                } 
        
        
                
        $conn->close();
         
?>