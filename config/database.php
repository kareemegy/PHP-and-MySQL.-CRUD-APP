<?php
$host = "localhost";
$db_name = "php_beginner_crud_level_1";
$username = "root";
$password = "";

try {
    $con = new PDO
        ("mysql:host={$host};dbname={$db_name}", $username, $password);
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}


