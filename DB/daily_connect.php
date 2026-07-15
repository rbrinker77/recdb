<?php
// DB/daily_connect.php

$host = 'mysqldb';       // Docker container service name
$user = 'dailyuser';
$password = 'VTG1nmn8xye9waeKrca';
$dbname = 'daily_tracker';

try {
    // Establish connection via PDO (built-in to official PHP Docker images)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $conn = new PDO($dsn, $user, $password, $options);

} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
