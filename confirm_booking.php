<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['kh_id'])) { header("Location: login_client.php"); exit(); }

$ma_tour = $_GET['id'] ?? 0;
try {
    $stmt = $pdo->prepare("SELECT * FROM tourdl WHERE maTour = ?");
    $stmt->execute([$ma_tour]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$tour) die("Tour không tồn tại!");

    // Lấy khuyến mãi có phan_tram > 0
    $today = date('Y-m-d');
    $stmt_km = $pdo->prepare("SELECT * FROM khuyenmai WHERE ngay_bat_dau <= ? AND ngay_ket_thuc >= ? AND phan_tram > 0");
    $stmt_km->execute([$today, $today]);
    $coupons = $stmt_km->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { die("Lỗi: " . $e->getMessage()); }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác Nhận Thanh Toán</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: white; padding: 30px; border-radius: 8px; width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .row { display: flex; justify-content: space-between; margin: 10px 0; }
        .total { font-size: 1.2em; font-weight: bold; color: #d9534f; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; font-weight: bold; cursor: pointer; margin-top: 15px; }
    </style>
</head>
<body>

<div class="box">
    <h2 style="text-align: center; color: #007bff;">XÁC NHẬN THANH TOÁN</h2>
    <h3><?php echo htmlspecialchars($tour['TenTour']); ?></h3>
    
    <form action="booking.php" method="POST">
        <input type="hidden" name="ma_tour" value="<?php echo $tour['maTour']; ?>">

        <div class="row">
            <span>Giá vé:</span>
            <span><?php echo number_format($tour['gia_ban']); ?> VNĐ</span>
        </div>

        <label>🎫 Mã Giảm Giá:</label>
        <select name="ma_khuyen_mai" id="voucher" onchange="tinhTien()" style="width: 100%; padding: 8px; margin-top: 5px;">
            <option value="0" data-percent="0">-- Không áp dụng --</option>
            <?php foreach ($coupons as $km): ?>
                <option value="<?php echo $km['ma_khuyen_mai']; ?>" data-percent="<?php echo $km['phan_tram']; ?>">
                    <?php echo htmlspecialchars($km['gia_tri']); ?> (Giảm <?php echo $km['phan_tram']; ?>%)
                </option>
            <?php endforeach; ?>
        </select>

        <div class="row" style="color: green;">
            <span>Được giảm:</span>
            <span id="giam_gia">- 0 VNĐ</span>
        </div>

        <div class="row total">
            <span>TỔNG CỘNG:</span>
            <span id="tong_tien"><?php echo number_format($tour['gia_ban']); ?> VNĐ</span>
        </div>

        <hr>
        <label>💳 Phương thức thanh toán:</label>
        <div style="margin-top: 5px;">
            <label><input type="radio" name="phuong_thuc" value="Tiền mặt" checked> Tiền mặt</label><br>
            <label><input type="radio" name="phuong_thuc" value="Chuyển khoản"> Chuyển khoản</label>
        </div>

        <button type="submit">XÁC NHẬN ĐẶT VÉ</button>
        <p style="text-align: center;"><a href="home.php">Hủy bỏ</a></p>
    </form>
</div>

<script>
    function tinhTien() {
        var giaGoc = <?php echo $tour['gia_ban']; ?>;
        var select = document.getElementById("voucher");
        var percent = select.options[select.selectedIndex].getAttribute("data-percent");
        
        var soTienGiam = giaGoc * (percent / 100);
        var tongTien = giaGoc - soTienGiam;

        document.getElementById("giam_gia").innerText = "- " + new Intl.NumberFormat('vi-VN').format(soTienGiam) + " VNĐ";
        document.getElementById("tong_tien").innerText = new Intl.NumberFormat('vi-VN').format(tongTien) + " VNĐ";
    }
</script>

</body>
</html>