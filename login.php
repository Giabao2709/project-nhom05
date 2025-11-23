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
require_once 'config/db.php'; // Nạp file kết nối CSDL (phải tạo $pdo trong file này)

$error = '';

// Nếu đã đăng nhập, chuyển thẳng vào index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Xử lý khi người dùng nhấn nút "Đăng nhập"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dùng null-coalescing để tránh undefined index
    $ten_dang_nhap = trim($_POST['ten_dang_nhap'] ?? '');
    $mat_khau = trim($_POST['mat_khau'] ?? '');

    if ($ten_dang_nhap === '' || $mat_khau === '') {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } else {
        try {
            // Tìm người dùng trong CSDL (prepared statement đã chống SQL injection)
            $sql = "SELECT * FROM quantrivien WHERE ten_dang_nhap = ? LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ten_dang_nhap]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra mật khẩu (giả sử mat_khau trong DB là password_hash)
            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                // Đăng nhập thành công, tái tạo session id để an toàn hơn
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['ho_ten'] ?? $user['ten_dang_nhap'];

                // Chuyển hướng đến trang quản trị
                header("Location: index.php");
                exit();
            } else {
                // Thông báo lỗi chung (không bật chi tiết user tồn tại hay không)
                $error = "Cảnh báo: Thông tin đăng nhập không đúng. Vui lòng kiểm tra lại!";
            }

        } catch (PDOException $e) {
            // Tránh hiện thông tin nội bộ cho người dùng (dev có thể log)
            // Nhưng để tiện debug bạn có thể bật dòng dưới (không khuyến nghị trên production)
            // $error = "Lỗi Hệ Thống: " . $e->getMessage();
            $error = "Lỗi Hệ Thống: Vui lòng thử lại sau hoặc liên hệ quản trị.";
            // Log lỗi thực tế ở server (ví dụ lỗi vào file log) - không hiển thị cho user
            error_log("Database error on login: " . $e->getMessage());
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
            <div class="error">⚠️ <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="POST" autocomplete="off">
            <div>
                <label for="ten_dang_nhap">Tên tài khoản</label>
                <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required placeholder="Nhập tài khoản admin..." value="<?php echo isset($ten_dang_nhap) ? htmlspecialchars($ten_dang_nhap, ENT_QUOTES, 'UTF-8') : ''; ?>">
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
