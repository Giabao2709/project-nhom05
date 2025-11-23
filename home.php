<?php
// home.php - Trang chủ dành cho Khách hàng
session_start();
require_once 'config/db.php';

// Lấy danh sách Tour mới nhất
try {
    $sql = "SELECT * FROM tourdl ORDER BY maTour DESC LIMIT 6";
    $stmt = $pdo->query($sql);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Du Lịch Việt Nam</title>

    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }

        /* Header */
        header { background-color: #007bff; color: white; padding: 15px 0; }
        .container { width: 85%; margin: 0 auto; }
        .navbar { display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .menu a { color: white; text-decoration: none; margin-left: 20px; font-weight: 500; }
        .menu a:hover { text-decoration: underline; }

        /* Banner */
        .banner { 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://source.unsplash.com/1600x900/?travel,nature'); 
            background-size: cover;
            background-position: center;
            height: 400px; 
            display: flex; align-items: center; justify-content: center; 
            color: white; text-align: center;
        }
        .banner h1 { font-size: 3em; margin: 0; }
        .banner p { font-size: 1.5em; }

        /* Tour List */
        .tour-section { padding: 40px 0; }
        .section-title { text-align: center; margin-bottom: 30px; color: #333; font-size: 2em; }
        
        .tour-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
        }
        
        .tour-card { 
            background: white; border-radius: 8px; overflow: hidden; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            transition: transform 0.3s; 
        }
        .tour-card:hover { transform: translateY(-5px); }
        
        .tour-img { width: 100%; height: 200px; object-fit: cover; background-color: #ddd; }
        .tour-info { padding: 20px; }
        .tour-title { font-size: 1.2em; font-weight: bold; margin-bottom: 10px; color: #007bff; }
        .tour-meta { color: #666; font-size: 0.9em; margin-bottom: 15px; }
        .tour-price { color: #d9534f; font-size: 1.3em; font-weight: bold; display: block; margin-bottom: 15px; }
        
        .btn-book { 
            display: block; width: 100%; padding: 10px; 
            background-color: #28a745; 
            color: white; text-align: center; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: bold; 
        }
        .btn-book:hover { background-color: #218838; }

        /* Footer */
        footer { background-color: #333; color: white; text-align: center; padding: 20px 0; margin-top: 40px; }
    </style>
</head>
<body>

    <header>
        <div class="container navbar">
            <div class="logo">VIVU TOUR ✈️</div>

            <div class="menu">
                <a href="home.php">Trang Chủ</a>
                <a href="#">Tour Trong Nước</a>
                <a href="#">Tour Quốc Tế</a>

                <?php if (isset($_SESSION['kh_id'])): ?>
                    <span style="margin-left: 20px; font-weight: bold;">
                        Xin chào, <?php echo htmlspecialchars($_SESSION['kh_name']); ?>
                    </span>
                    <a href="logout_client.php" style="background: #dc3545; padding: 5px 10px; border-radius: 4px;">Thoát</a>
                <?php else: ?>
                    <a href="login_client.php" style="background: #28a745; padding: 5px 10px; border-radius: 4px;">Đăng nhập</a>
                    <a href="register_client.php" style="background: #17a2b8; padding: 5px 10px; border-radius: 4px;">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="banner">
        <div>
            <h1>KHÁM PHÁ THẾ GIỚI</h1>
            <p>Cùng chúng tôi trải nghiệm những hành trình tuyệt vời</p>
        </div>
    </div>

    <div class="container tour-section">
        <h2 class="section-title">Các Tour Nổi Bật</h2>
        
        <div class="tour-grid">
            <?php if (empty($tours)): ?>
                <p style="text-align: center; width: 100%;">Chưa có tour nào được mở bán.</p>
            <?php else: ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <img src="https://source.unsplash.com/random/400x300/?travel,landmark&sig=<?php echo $tour['maTour']; ?>" class="tour-img">

                        <div class="tour-info">
                            <div class="tour-title"><?php echo htmlspecialchars($tour['TenTour']); ?></div>

                            <div class="tour-meta">
                                📅 Khởi hành: <?php echo date("d/m/Y", strtotime($tour['ngay_khoi_hanh'])); ?><br>
                                ⏱ Thời gian: <?php echo htmlspecialchars($tour['thoi_gian']); ?><br>
                                👥 Số chỗ: <?php echo htmlspecialchars($tour['so_cho_toi_da']); ?>
                            </div>

                            <span class="tour-price"><?php echo number_format($tour['gia_ban']); ?> VNĐ</span>

                            <a href="confirm_booking.php?id=<?php echo $tour['maTour']; ?>" 
                               class="btn-book" 
                               onclick="return confirm('Bạn có muốn đặt tour này không?');">
                               ĐẶT NGAY
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            &copy; <?php echo date("Y"); ?> - Công Ty Du Lịch Nhóm 05. All rights reserved.
        </div>
    </footer>

</body>
</html>
