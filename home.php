<?php
session_start();
// 1. KẾT NỐI CSDL (QUAN TRỌNG: Phải có đoạn này mới không bị lỗi $tours)
require_once 'config/db.php';

try {
    // 2. Lấy danh sách 6 tour mới nhất
    $sql = "SELECT * FROM tourdl ORDER BY id DESC LIMIT 6";
    $stmt = $pdo->query($sql);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tours = []; // Nếu lỗi thì gán mảng rỗng để web không bị chết
    echo "Lỗi kết nối: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vivu Vietnam - Trải nghiệm sự khác biệt</title>
    
    <!-- 3. Nhúng Font chữ Google (Poppins) cho đẹp -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- 4. Nhúng Icon và CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="layouts/client_style.css">
</head>
<body>

    <!-- THANH MENU (NAVBAR) -->
    <nav class="navbar">
        <div class="logo">VIVU VIETNAM <i class="fas fa-paper-plane"></i></div>
        <div class="menu">
            <a href="#">Trang Chủ</a>
            <a href="#tour-hot">Tour Hot</a>
            <a href="#">Tin Tức</a>
            <a href="#">Liên Hệ</a>
        </div>
        
        <!-- Kiểm tra nếu khách đã đăng nhập -->
        <?php if (isset($_SESSION['user_name'])): ?>
             <div class="user-action">
                <span style="color:white; margin-right: 10px;">Xin chào, <?php echo $_SESSION['user_name']; ?></span>
                <a href="logout_client.php" class="btn-login btn-logout">Thoát</a>
             </div>
        <?php else: ?>
            <a href="login_client.php" class="btn-login">Đăng Nhập</a>
        <?php endif; ?>
    </nav>

    <!-- HERO BANNER (PHẦN BÌA ĐẦU TRANG) -->
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
            <p>Những chuyến đi được yêu thích nhất mùa hè này</p>
        </div>

        <div class="tour-grid">
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <div class="card-header">
                            <?php 
                                $hinh = !empty($tour['hinh_anh']) ? "uploads/".$tour['hinh_anh'] : "https://source.unsplash.com/random/400x300/?travel"; 
                            ?>
                            <img src="<?php echo $hinh; ?>" alt="<?php echo $tour['ten_tour']; ?>">
                            <span class="badge-hot">HOT</span>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span><i class="far fa-clock"></i> 3 Ngày 2 Đêm</span>
                                <span><i class="fas fa-user-friends"></i> 20 chỗ</span>
                            </div>
                            <h3 class="tour-title"><?php echo $tour['ten_tour']; ?></h3>
                            <div class="card-footer">
                                <div class="price">
                                    <span class="currency">₫</span>
                                    <?php echo number_format($tour['gia'], 0, ',', '.'); ?>
                                </div>
                                <a href="booking.php?id=<?php echo $tour['id']; ?>" class="btn-book">Chi tiết <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-box-open"></i>
                    <p>Chưa có tour nào được cập nhật.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <h3>Vivu Vietnam</h3>
            <p>Hệ thống đặt tour du lịch uy tín hàng đầu.</p>
            <ul class="socials">
                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Project Nhom 05. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>