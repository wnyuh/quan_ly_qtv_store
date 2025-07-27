<?php
// public/tests/test_ctdh.php
ini_set('display_errors',1);
error_reporting(E_ALL);

// 1) Load PDO
require __DIR__ . '/../../config/db.php'; // trả về $pdo

// 2) Load model
require __DIR__ . '/../../app/Models/chitietdonhang.php';

// 3) Khởi và gọi
$model = new chitietdonhang($pdo);
$data  = $model->findByOrder(1); // thử với ma_don_hang = 1

echo '<pre>';
print_r($data);
echo '</pre>';
