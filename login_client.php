<?php
session_start();
require_once 'config/db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Kiểm tra email trong bảng khách hàng
    $stmt = $pdo->prepare("SELECT * FROM khachhang WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mat_khau'])) {
        // --- QUAN TRỌNG: LƯU SESSION ĐỂ TRANG CHỦ HIỂN THỊ ---
        
        // Lưu ID để sau này dùng cho việc Đặt tour (Booking)
        // Kiểm tra xem trong CSDL cột là 'id' hay 'ma_khach_hang'
        // Nếu code cũ của bạn dùng 'ma_khach_hang' thì giữ nguyên dòng dưới:
        $_SESSION['client_id'] = $user['ma_khach_hang']; 

        // Lưu Tên để hiển thị trên thanh Menu (home.php)
        $_SESSION['client_name'] = $user['ho_ten']; 
        
        // Chuyển hướng về trang chủ
        header("Location: home.php");
        exit();
    } else {
        $msg = "Email hoặc mật khẩu không đúng!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập Khách Hàng - Vivu Vietnam</title>
    <!-- Tái sử dụng CSS của Client để đồng bộ giao diện -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        h2 { color: #333; margin-bottom: 20px; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #f43f5e;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #e11d48; }
        .links { margin-top: 15px; font-size: 14px; }
        .links a { color: #0ea5e9; text-decoration: none; margin: 0 5px; }
        .links a:hover { text-decoration: underline; }
        .error { color: red; font-size: 13px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Khách Hàng Đăng Nhập</h2>
        <?php if ($msg): ?>
            <p class="error"><?php echo $msg; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="Nhập Email của bạn" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng Nhập</button>
        </form>

        <div class="links">
            <a href="register_client.php">Đăng ký tài khoản mới</a> | 
            <a href="home.php">Về trang chủ</a>
        </div>
    </div>
</body>
</html>