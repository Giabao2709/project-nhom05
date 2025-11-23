<?php
session_start();
require_once 'config/db.php';

// Lấy danh sách Tour từ CSDL (Giới hạn 6 tour mới nhất)
try {
    $sql = "SELECT * FROM tourdl ORDER BY id DESC LIMIT 6";
    $stmt = $pdo->query($sql);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Vivu Vietnam</title>
    <!-- Nhúng file CSS mới tạo -->
    <link rel="stylesheet" href="layouts/client_style.css">
    <!-- Nhúng Font Awesome để có icon đẹp -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Header / Menu (Giữ nguyên hoặc include file header của bạn) -->
    <?php include 'layouts/header_client.php'; // Hoặc file header bạn đang dùng ?>

    <!-- HERO SECTION (BANNER) -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1>Khám Phá Vẻ Đẹp Việt Nam</h1>
            <p>Hơn 1000+ tour du lịch hấp dẫn đang chờ đón bạn trải nghiệm</p>
            <a href="#tour-list" class="btn-explore">Đặt Tour Ngay <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <!-- DANH SÁCH TOUR (GRID VIEW) -->
    <div id="tour-list" class="container">
        
        <div class="section-title">
            <h2>Tour Du Lịch Nổi Bật</h2>
        </div>

        <div class="tour-container">
            <?php if ($tours): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <div class="tour-img">
                            <!-- Nếu có ảnh thì hiện, không thì hiện ảnh mặc định -->
                            <?php 
                                $imgSrc = !empty($tour['hinh_anh']) ? "uploads/" . $tour['hinh_anh'] : "https://via.placeholder.com/400x250"; 
                            ?>
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo $tour['ten_tour']; ?>">
                            <span class="tour-price-badge">-10%</span> <!-- Ví dụ badge giảm giá -->
                        </div>
                        
                        <div class="tour-content">
                            <h3 class="tour-title"><?php echo $tour['ten_tour']; ?></h3>
                            
                            <div class="tour-info">
                                <span><i class="far fa-clock"></i> 3 Ngày 2 Đêm</span> <!-- Ví dụ -->
                                <span><i class="fas fa-user-friends"></i> Còn trống</span>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="tour-price">
                                    <?php echo number_format($tour['gia'], 0, ',', '.'); ?> đ
                                </div>
                            </div>

                            <a href="booking.php?id=<?php echo $tour['id']; ?>" class="btn-book">
                                <i class="fas fa-ticket-alt"></i> Đặt Vé Ngay
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; width: 100%;">Chưa có tour nào được cập nhật.</p>
            <?php endif; ?>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="client-footer">
        <p>© 2025 Vivu Vietnam - Hệ thống đặt tour du lịch hàng đầu.</p>
        <p>Nhóm 05 - Lập trình Web</p>
    </footer>

</body>
</html>