<?php
// layouts/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$ten_nguoi_dung = $_SESSION['user_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo $page_title ?? 'Hệ Thống Quản Trị'; ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #eef1f7;
        }
        .wrapper {
            display: flex; 
            width: 100%; 
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b, #0f172a);
            color: white;
            padding: 25px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
        }
        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 25px;
            text-align: center;
        }
        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 15px;
            display: block;
            border-radius: 6px;
            transition: 0.25s;
            font-size: 15px;
        }
        .sidebar a:hover {
            background: #334155;
            color: #ffffff;
            transform: translateX(4px);
        }

        /* Main content */
        .main-content {
            flex: 1;
            padding: 25px;
            background: #f8fafc;
        }

        /* Header bar */
        header {
            background: white;
            padding: 18px 22px;
            border-radius: 12px;
            box-shadow: 0 2px 9px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        header h2 {
            font-size: 22px;
            color: #1e293b;
        }

        .user-info {
            font-size: 16px;
            color: #475569;
        }
        .user-info strong {
            color: #1e40af;
        }
    </style>
</head>

<body>
<div class="wrapper">

    <?php include_once 'layouts/sidebar.php'; ?>

    <div class="main-content">

        <header>
            <h2><?php echo $page_title ?? 'Trang Chủ'; ?></h2>
            <div class="user-info">
                Xin chào, <strong><?php echo htmlspecialchars($ten_nguoi_dung); ?></strong>
            </div>
        </header>

        <main>
