<?php
require_once 'config/db.php';
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];

    try {
        $sql = "INSERT INTO khachhang (ho_ten, email, mat_khau, so_dien_thoai, dia_chi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ho_ten, $email, $pass, $sdt, $dia_chi]);
        echo "<script>alert('Đăng ký thành công! Hãy đăng nhập.'); window.location='login_client.php';</script>";
    } catch (PDOException $e) {
        $msg = "Lỗi: Email này có thể đã tồn tại.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Đăng Ký Thành Viên</title></head>
<body style="font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px;">
    <form method="POST" style="width: 300px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
        <h3 style="text-align: center;">ĐĂNG KÝ THÀNH VIÊN</h3>
        <p style="color: red;"><?php echo $msg; ?></p>
        <input type="text" name="ho_ten" placeholder="Họ và tên" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
        <input type="email" name="email" placeholder="Email" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
        <input type="password" name="password" placeholder="Mật khẩu" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
        <input type="text" name="sdt" placeholder="Số điện thoại" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
        <input type="text" name="dia_chi" placeholder="Địa chỉ" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
        <button type="submit" style="width: 100%; padding: 10px; background: #28a745; color: white; border: none;">Đăng Ký</button>
        <p><a href="login_client.php">Đã có tài khoản? Đăng nhập</a></p>
        <p><a href="home.php">Về trang chủ</a></p>
    </form>
</body>
</html>