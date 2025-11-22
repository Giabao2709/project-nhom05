<?php
session_start();

// Xóa thông tin đăng nhập của khách hàng
unset($_SESSION['kh_id']);
unset($_SESSION['kh_name']);

// Quay về trang chủ
header("Location: home.php");
exit();
?>