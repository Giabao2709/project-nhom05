<?php
// layouts/header.php
session_start(); // Khởi động session

// BẢO VỆ TRANG: Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang login
    header("Location: login.php");
    exit();
}

// Nếu đã đăng nhập, chào mừng người dùng
$ten_nguoi_dung = $_SESSION['user_name'] ?? 'projectnhom05';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Trang Quản Trị'; ?></title>
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>
    <div class="wrapper">
        
        <?php include_once 'layouts/sidebar.php'; // Tải sidebar ?>
        
        <div class="main-content">
            <header>
                
            </header>
            
            <main>
               