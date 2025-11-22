<?php
session_start();
require_once 'config/db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM khachhang WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mat_khau'])) {
        // Lưu session khách hàng (Khác với session admin)
        $_SESSION['kh_id'] = $user['ma_khach_hang'];
        $_SESSION['kh_name'] = $user['ho_ten'];
        header("Location: home.php");
        exit();
    } else {
        $msg = "Email hoặc mật khẩu không đúng!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Đăng Nhập Khách Hàng</title></head>
<body style="font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px;">
    <form method="POST" style="width: 300px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
        <h3 style="text-align: center;">KHÁCH HÀNG ĐĂNG NHẬP</h3>
        <p style="color: red;"><?php echo $msg; ?></p>
        <input type="email" name="email" placeholder="Email" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
        <input type="password" name="password" placeholder="Mật khẩu" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
        <button type="submit" style="width: 100%; padding: 10px; background: #007bff; color: white; border: none;">Đăng Nhập</button>
        <p><a href="register_client.php">Chưa có tài khoản? Đăng ký</a></p>
        <p><a href="home.php">Về trang chủ</a></p>
    </form>
</body>
</html>