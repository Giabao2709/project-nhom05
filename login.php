<?php
// login.php
session_start(); // Bắt đầu session
require_once 'config/db.php'; // Nạp file kết nối CSDL

$error = '';

// Nếu đã đăng nhập, chuyển thẳng vào index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Xử lý khi người dùng nhấn nút "Đăng nhập"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_dang_nhap = $_POST['ten_dang_nhap'];
    $mat_khau = $_POST['mat_khau'];

    if (empty($ten_dang_nhap) || empty($mat_khau)) {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } else {
        try {
            // Tìm người dùng trong CSDL
            $sql = "SELECT * FROM quantrivien WHERE ten_dang_nhap = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ten_dang_nhap]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra mật khẩu
            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                // Đăng nhập thành công, lưu thông tin vào Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['ho_ten'];
                
                // Chuyển hướng đến trang quản trị
                header("Location: index.php");
                exit();
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
            }

        } catch (PDOException $e) {
            $error = "Lỗi CSDL: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Trang Quản Trị</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px; text-align: center; }
        .login-box h2 { margin-bottom: 20px; }
        .login-box div { margin-bottom: 15px; text-align: left; }
        .login-box label { display: block; margin-bottom: 5px; }
        .login-box input { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .login-box button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .error { color: red; background: #fee; border: 1px solid red; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Đăng Nhập</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div>
                <label for="ten_dang_nhap">Tên đăng nhập:</label>
                <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required>
            </div>
            <div>
                <label for="mat_khau">Mật khẩu:</label>
                <input type="password" id="mat_khau" name="mat_khau" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>