<?php
/**
 * FILE CẤU HÌNH CƠ SỞ DỮ LIỆU
 * Người phụ trách: SV1 - Hữu Thuận
 */

$host = 'localhost';
$dbname = 'quanlytour';     // Tên CSDL
$username = 'root';         // User mặc định XAMPP
$password = '';             // Pass mặc định XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Nếu lỗi kết nối, dừng và báo lỗi
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>