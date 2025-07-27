<?php
// config/db.php

$host    = '127.0.0.1';
$port    = 3307;
$dbName  = 'Store_Iphone';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Trong production bạn có thể log lỗi chứ không die()
    die('Kết nối CSDL thất bại: ' . $e->getMessage());
}

return $pdo;
