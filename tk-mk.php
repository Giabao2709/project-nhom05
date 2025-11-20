<?php
// Đặt mật khẩu mới của bạn vào đây
$mat_khau_moi = 'nhom05'; 

// Mã hóa mật khẩu
$hash = password_hash($mat_khau_moi, PASSWORD_DEFAULT);

echo "Tài khoản mới: projectnhom05<br>";
echo "Mật khẩu mới: $mat_khau_moi<br>";
echo "Chuỗi mã hóa (Copy chuỗi này):<br>";
echo "<textarea style='width:500px; height: 70px;'>$hash</textarea>";
?>