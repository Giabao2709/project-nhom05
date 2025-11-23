<?php
// layouts/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$ten_nguoi_dung = $_SESSION['user_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Nhúng file CSS giao diện chính -->
<link rel="stylesheet" href="layouts/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title><?php echo $page_title ?? 'Hệ Thống Quản Trị'; ?></title>
    
    <link rel="stylesheet" href="assets/style.css"> 
    
    <style>
        /* CSS nhanh cho layout */
        body { font-family: Arial, sans-serif; margin: 0; display: flex; }
        .wrapper { display: flex; width: 100%; min-height: 100vh; }
        .sidebar { width: 250px; background: #343a40; color: white; padding: 20px; }
        .sidebar a { color: #ddd; text-decoration: none; display: block; padding: 10px; }
        .sidebar a:hover { background: #495057; color: white; }
        .main-content { flex: 1; padding: 20px; background: #f4f6f9; }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include_once 'layouts/sidebar.php'; ?>
        
        <div class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 20px;">
                <h2><?php echo $page_title ?? 'Trang Chủ'; ?></h2>
                <div>
                    Xin chào, <strong><?php echo htmlspecialchars($ten_nguoi_dung); ?></strong>
                </div>
            </header>
            
            <main>