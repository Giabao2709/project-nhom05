<?php
session_start();
require_once 'config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['kh_id'])) {
    header("Location: login_client.php");
    exit();
}

$ma_tour = $_GET['id'] ?? 0;

try {
    // 1. Lấy thông tin Tour
    $stmt = $pdo->prepare("SELECT * FROM tourdl WHERE maTour = ?");
    $stmt->execute([$ma_tour]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tour) die("Tour không tồn tại!");

    // 2. Lấy danh sách Khuyến mãi còn hạn
    $today = date('Y-m-d');
    $sql_km = "SELECT * FROM khuyenmai WHERE ngay_bat_dau <= ? AND ngay_ket_thuc >= ?";
    $stmt_km = $pdo->prepare($sql_km);
    $stmt_km->execute([$today, $today]);
    $coupons = $stmt_km->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đặt Tour</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .checkout-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #007bff; margin-top: 0; }
        .tour-info { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .total-row { font-size: 1.2em; font-weight: bold; color: #d9534f; margin-top: 15px; border-top: 2px solid #eee; padding-top: 10px; }
        select, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #28a745; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background: #218838; }
        .back-link { display: block; text-align: center; margin-top: 10px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

<div class="checkout-box">
    <h2>XÁC NHẬN THANH TOÁN</h2>
    
    <div class="tour-info">
        <h3><?php echo htmlspecialchars($tour['TenTour']); ?></h3>
        <p>📅 Ngày đi: <?php echo date("d/m/Y", strtotime($tour['ngay_khoi_hanh'])); ?></p>
    </div>

    <form action="booking.php" method="POST">
        <input type="hidden" name="ma_tour" value="<?php echo $tour['maTour']; ?>">
        <input type="hidden" name="gia_goc" value="<?php echo $tour['gia_ban']; ?>">

        <div class="row">
            <span>Giá vé:</span>
            <span><?php echo number_format($tour['gia_ban']); ?> VNĐ</span>
        </div>

        <label for="voucher">🎫 Chọn Mã Giảm Giá:</label>
        <select name="ma_khuyen_mai" id="voucher" onchange="calculateTotal()">
            <option value="0" data-percent="0">-- Không áp dụng --</option>
            <?php foreach ($coupons as $km): ?>
                <option value="<?php echo $km['ma_khuyen_mai']; ?>" data-percent="<?php echo $km['phan_tram']; ?>">
                    <?php echo htmlspecialchars($km['gia_tri']); ?> (Giảm <?php echo $km['phan_tram']; ?>%)
                </option>
            <?php endforeach; ?>
        </select>

        <div class="row" style="color: green;">
            <span>Được giảm:</span>
            <span id="discount_amount">- 0 VNĐ</span>
        </div>

        <div class="row total-row">
            <span>TỔNG THANH TOÁN:</span>
            <span id="final_total"><?php echo number_format($tour['gia_ban']); ?> VNĐ</span>
        </div>

        <button type="submit">XÁC NHẬN ĐẶT & THANH TOÁN</button>
    </form>
    
    <a href="home.php" class="back-link">Quay lại trang chủ</a>
</div>

<script>
    function calculateTotal() {
        var giaGoc = <?php echo $tour['gia_ban']; ?>;
        var selectBox = document.getElementById("voucher");
        var selectedOption = selectBox.options[selectBox.selectedIndex];
        var percent = selectedOption.getAttribute("data-percent"); // Lấy % giảm

        // Tính toán
        var soTienGiam = giaGoc * (percent / 100);
        var tongTien = giaGoc - soTienGiam;

        // Hiển thị lại (Format số tiền)
        document.getElementById("discount_amount").innerText = "- " + new Intl.NumberFormat().format(soTienGiam) + " VNĐ";
        document.getElementById("final_total").innerText = new Intl.NumberFormat().format(tongTien) + " VNĐ";
    }
</script>

</body>
</html>