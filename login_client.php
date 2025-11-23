<?php
session_start();
require_once 'config/db.php';

// Chỉ xử lý khi có dữ liệu gửi đến
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Kiểm tra trong CSDL
    $stmt = $pdo->prepare("SELECT * FROM khachhang WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mat_khau'])) {
        // --- ĐĂNG NHẬP THÀNH CÔNG ---
        $_SESSION['client_id'] = $user['ma_khach_hang']; 
        $_SESSION['client_name'] = $user['ho_ten']; 
        
        // Quay về trang chủ
        header("Location: home.php");
        exit();
    } else {
        // --- ĐĂNG NHẬP THẤT BẠI ---
        // Quay về trang chủ kèm thông báo lỗi trên URL
        header("Location: home.php?login_error=1");
        exit();
    }
} else {
    // Nếu ai đó cố tình truy cập trực tiếp file này -> Đẩy về trang chủ
    header("Location: home.php");
    exit();
}
?>