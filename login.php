<?php
/**
 * SYSTEM LOGIN (Hệ thống Đăng nhập)
 * --------------------------------
 * Chức năng: Xác thực người dùng và khởi tạo Session.
 * Bảo mật: Sử dụng password_verify() để kiểm tra mật khẩu mã hóa.
 * Cập nhật: Xử lý dữ liệu đầu vào (trim) và thông báo lỗi chi tiết.
 * Dev: SV3 - Gia Bảo
 */

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
    // SV3: Dùng trim() để loại bỏ khoảng trắng thừa
    $ten_dang_nhap = trim($_POST['ten_dang_nhap']);
    $mat_khau = trim($_POST['mat_khau']);

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
                // SV3: Cập nhật thông báo lỗi chi tiết hơn
                $error = "Cảnh báo: Thông tin đăng nhập không đúng. Vui lòng kiểm tra lại!";
            }

        } catch (PDOException $e) {
            $error = "Lỗi Hệ Thống: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System - Nhóm 05</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); width: 320px; text-align: center; }
        .login-box h2 { margin-bottom: 25px; color: #333; font-weight: 600; }
        .login-box div { margin-bottom: 20px; text-align: left; }
        .login-box label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.95em; color: #555; }
        .login-box input { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s; }
        .login-box input:focus { border-color: #007bff; outline: none; }
        .login-box button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: background 0.3s; }
        .login-box button:hover { background: #0056b3; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin-bottom: 20px; border-radius: 6px; font-size: 0.9em; text-align: left; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>ĐĂNG NHẬP</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error">⚠️ <?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div>
                <label for="ten_dang_nhap">Tên tài khoản</label>
                <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required placeholder="Nhập tài khoản admin...">
            </div>
            <div>
                <label for="mat_khau">Mật khẩu</label>
                <input type="password" id="mat_khau" name="mat_khau" required placeholder="Nhập mật khẩu...">
            </div>
            <button type="submit">Truy Cập Hệ Thống</button>
        </form>
    </div>
</body>
</html>