<?php
session_start();

// 1. KẾT NỐI CSDL
require_once 'config/db.php';

// 2. LOGIC KIỂM TRA ĐĂNG NHẬP
$is_logged_in = false;
$user_name = "Khách";

if (isset($_SESSION['client_name']) && !empty($_SESSION['client_name'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['client_name'];
} elseif (isset($_SESSION['kh_name']) && !empty($_SESSION['kh_name'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['kh_name'];
}

// 3. LẤY DANH SÁCH TOUR TỪ CSDL
$tours = [];
$error_db = "";

try {
    // --- SỬA LỖI Ở ĐÂY: Thay 'id' thành 'maTour' ---
    $sql = "SELECT * FROM tourdl ORDER BY maTour DESC LIMIT 6";
    $stmt = $pdo->query($sql);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_db = "Lỗi kết nối: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vivu Vietnam - Trải nghiệm sự khác biệt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="layouts/client_style.css">
    
    <!-- CSS MODAL ĐĂNG NHẬP -->
    <style>
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); z-index: 2000;
            display: none; justify-content: center; align-items: center;
            backdrop-filter: blur(5px);
        }
        .login-popup {
            background: white; width: 400px; padding: 40px;
            border-radius: 20px; text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            position: relative; animation: slideDown 0.4s ease;
        }
        @keyframes slideDown { from {transform: translateY(-50px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
        
        .login-popup h2 { color: #333; margin-bottom: 30px; font-size: 1.8rem; }
        .input-group { margin-bottom: 20px; text-align: left; }
        .input-group input {
            width: 100%; padding: 15px; border: 2px solid #eee;
            border-radius: 10px; font-size: 1rem; outline: none; transition: 0.3s;
        }
        .input-group input:focus { border-color: #0ea5e9; background: #f0f9ff; }
        
        .btn-submit {
            width: 100%; padding: 15px; background: #f43f5e; color: white;
            border: none; border-radius: 10px; font-size: 1.1rem; font-weight: bold;
            cursor: pointer; transition: 0.3s; box-shadow: 0 5px 15px rgba(244, 63, 94, 0.4);
        }
        .btn-submit:hover { background: #e11d48; transform: scale(1.02); }
        
        .close-btn {
            position: absolute; top: 15px; right: 20px; font-size: 2rem;
            color: #999; cursor: pointer;
        }
        .close-btn:hover { color: #333; }
        .error-msg { color: red; background: #ffe4e6; padding: 10px; border-radius: 8px; margin-bottom: 20px; display: none; }
        
        .no-data {
            grid-column: 1 / -1; text-align: center; padding: 50px;
            background: #fff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .no-data i { font-size: 3rem; color: #cbd5e1; margin-bottom: 15px; }
    </style>
</head>
<body>

    <!-- THANH MENU -->
    <nav class="navbar">
        <div class="logo">VIVU VIETNAM <i class="fas fa-paper-plane"></i></div>
        <div class="menu">
            <a href="home.php">Trang Chủ</a>
            <a href="#tour-hot">Tour Hot</a>
            <a href="#">Tin Tức</a>
            <a href="#">Liên Hệ</a>
        </div>
        
        <div class="user-action">
            <?php if ($is_logged_in): ?>
                <span style="color: white; font-weight: 600; margin-right: 15px;">
                    <i class="fas fa-user-circle"></i> Xin chào, <?php echo htmlspecialchars($user_name); ?>
                </span>
                <a href="logout_client.php" class="btn-login btn-logout">Thoát</a>
            <?php else: ?>
                <a href="#" onclick="openLogin()" class="btn-login">Đăng Nhập</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- HERO BANNER -->
    <div class="hero-banner">
        <div class="overlay"></div>
        <div class="hero-content">
            <span class="subtitle">Khám phá vẻ đẹp bất tận</span>
            <h1>VIỆT NAM TRONG TẦM TAY</h1>
            <p>Hơn 500+ điểm đến hấp dẫn đang chờ bạn trải nghiệm ngay hôm nay.</p>
            <a href="#tour-hot" class="btn-explore">Đặt Tour Ngay <i class="fas fa-arrow-down"></i></a>
        </div>
    </div>

    <!-- DANH SÁCH TOUR -->
    <div id="tour-hot" class="container">
        <div class="section-header">
            <h2>TOUR DU LỊCH NỔI BẬT</h2>
            <div class="divider"></div>
            <?php if (!empty($error_db)): ?>
                <p style="color: red;">⚠️ <?php echo $error_db; ?></p>
            <?php endif; ?>
        </div>

        <div class="tour-grid">
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <div class="card-header">
                            <?php 
                                // --- CẬP NHẬT TÊN CỘT THEO CSDL CỦA BẠN ---
                                // Ảnh -> hinh_anh (nếu cột khác hãy sửa ở đây)
                                $hinh = "https://source.unsplash.com/random/400x300/?travel"; 
                                if (!empty($tour['hinh_anh']) && file_exists("uploads/".$tour['hinh_anh'])) {
                                    $hinh = "uploads/".$tour['hinh_anh'];
                                }
                            ?>
                            <img src="<?php echo $hinh; ?>">
                            <span class="badge-hot">HOT</span>
                        </div>
                        <div class="card-body">
                            <!-- Tên Tour -> TenTour -->
                            <h3 class="tour-title"><?php echo htmlspecialchars($tour['TenTour']); ?></h3>
                            <div class="card-footer">
                                <div class="price">
                                    <!-- Giá -> gia_ban -->
                                    <?php echo number_format($tour['gia_ban'], 0, ',', '.'); ?> ₫
                                </div>
                                <!-- ID -> maTour -->
                                <a href="booking.php?id=<?php echo $tour['maTour']; ?>" class="btn-book">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-box-open"></i>
                    <p>Chưa có tour nào được cập nhật.</p>
                    <small>Hãy vào trang Admin thêm vài tour mới nhé!</small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- POPUP ĐĂNG NHẬP -->
    <div class="modal-overlay" id="loginModal">
        <div class="login-popup">
            <span class="close-btn" onclick="closeLogin()">&times;</span>
            <h2>Khách Hàng Đăng Nhập</h2>
            <div id="loginError" class="error-msg">Email hoặc mật khẩu không chính xác!</div>
            <form action="login_client.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Nhập Email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>
                <button type="submit" class="btn-submit">Đăng Nhập</button>
            </form>
            <p style="margin-top: 20px; font-size: 0.9rem;">
                Chưa có tài khoản? <a href="register_client.php" style="color: #0ea5e9;">Đăng ký ngay</a>
            </p>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function openLogin() { document.getElementById('loginModal').style.display = 'flex'; }
        function closeLogin() { document.getElementById('loginModal').style.display = 'none'; }
        
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('login_error')) {
            openLogin();
            document.getElementById('loginError').style.display = 'block';
        }
    </script>

</body>
</html>