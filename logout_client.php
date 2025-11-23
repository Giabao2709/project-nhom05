<?php
session_start();
// Xóa toàn bộ session
session_unset();
session_destroy();

// Chuyển về trang chủ hoặc trang đăng nhập
header("Location: home.php");
exit();
?>