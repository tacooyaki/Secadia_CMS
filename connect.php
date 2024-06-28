<?php

        define('DB_USER', 'serveruser');
        define('DB_PASSWORD', 'gorgonzola7!');
        define('DB_NAME', 'serverside');
        define('DB_HOST', 'localhost');

    //  PDO is PHP Data Objects
    //  mysqli <-- BAD. 
    //  PDO <-- GOOD.
    try {
        // Try creating new PDO connection to MySQL.
        $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER, DB_PASSWORD);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die(); // Force execution to stop on errors.
    }
