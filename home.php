<?php
session_start();
require_once 'config/db.php';
try {
    $tours = $pdo->query("SELECT * FROM tourdl ORDER BY maTour DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { die("Lỗi: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vivu Travel - Khám Phá Việt Nam</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f8f9fa; }
        /* Header Xịn */
        nav { background: #fff; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #007bff; }
        .logo i { margin-right: 10px; }
        .menu a { margin-left: 20px; text-decoration: none; color: #555; font-weight: 500; transition: 0.3s; }
        .menu a:hover { color: #007bff; }
        .btn-login { background: #007bff; color: white !important; padding: 8px 20px; border-radius: 20px; }
        
        /* Banner */
        .hero { background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://source.unsplash.com/1600x900/?travel,beach'); height: 500px; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center; color: white; }
        .hero h1 { font-size: 3.5rem; margin-bottom: 10px; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); }
        
        /* Tour Grid */
        .container { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
        .section-title { text-align: center; margin-bottom: 40px; color: #333; font-size: 2rem; text-transform: uppercase; letter-spacing: 2px; }
        .tour-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        
        /* Tour Card Xịn */
        .card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); transition: 0.3s; border: none; }
        .card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
        .card-img { height: 200px; width: 100%; object-fit: cover; }
        .card-body { padding: 20px; }
        .card-title { font-size: 1.2rem; font-weight: bold; color: #333; margin-bottom: 10px; height: 54px; overflow: hidden; }
        .card-meta { display: flex; justify-content: space-between; color: #777; font-size: 0.9rem; margin-bottom: 15px; }
        .card-price { font-size: 1.4rem; color: #e74c3c; font-weight: bold; display: block; margin-bottom: 15px; }
        .btn-book { display: block; width: 100%; padding: 12px; background: #007bff; color: white; text-align: center; text-decoration: none; border-radius: 8px; font-weight: bold; transition: 0.3s; }
        .btn-book:hover { background: #0056b3; }
    </style>
</head>
<body>

    <nav>
        <div class="logo"><i class="fas fa-globe-asia"></i> VIVU VIETNAM</div>
        <div class="menu">
            <a href="home.php">Trang Chủ</a>
            <a href="#">Tour Hot</a>
            <a href="#">Liên Hệ</a>
            <?php if (isset($_SESSION['kh_id'])): ?>
                <a href="#" class="btn-login">Chào, <?php echo $_SESSION['kh_name']; ?></a>
                <a href="logout_client.php">Thoát</a>
            <?php else: ?>
                <a href="login_client.php" class="btn-login">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero">
        <div>
            <h1>KHÁM PHÁ VẺ ĐẸP VIỆT NAM</h1>
            <p>Hơn 1000+ tour du lịch hấp dẫn đang chờ đón bạn</p>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title">Tour Nổi Bật</h2>
        <div class="tour-grid">
            <?php foreach ($tours as $tour): ?>
                <div class="card">
                    <img src="https://source.unsplash.com/random/400x300/?travel,<?php echo $tour['maTour']; ?>" class="card-img">
                    <div class="card-body">
                        <div class="card-title"><?php echo htmlspecialchars($tour['TenTour']); ?></div>
                        <div class="card-meta">
                            <span><i class="far fa-clock"></i> <?php echo $tour['thoi_gian']; ?></span>
                            <span><i class="fas fa-user-friends"></i> <?php echo $tour['so_cho_toi_da']; ?> chỗ</span>
                        </div>
                        <span class="card-price"><?php echo number_format($tour['gia_ban']); ?> ₫</span>
                        <a href="confirm_booking.php?id=<?php echo $tour['maTour']; ?>" class="btn-book">ĐẶT NGAY <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>