<?php
// modules/dondattour/add.php

$page_title = 'Thêm Đơn Đặt Tour Mới';
$error_message = null;

// --- Lấy dữ liệu cho các Dropdown ---
try {
    // 1. Lấy danh sách Khách hàng
    $stmt_kh = $pdo->query("SELECT ma_khach_hang, ho_ten FROM khachhang ORDER BY ho_ten");
    $ds_khachhang = $stmt_kh->fetchAll(PDO::FETCH_ASSOC);

    // 2. Lấy danh sách Tour
    $stmt_tour = $pdo->query("SELECT maTour, TenTour FROM tourdl ORDER BY TenTour");
    $ds_tour = $stmt_tour->fetchAll(PDO::FETCH_ASSOC);

    // 3. Lấy danh sách Khuyến mãi
    $stmt_km = $pdo->query("SELECT ma_khuyen_mai, gia_tri FROM khuyenmai ORDER BY ma_khuyen_mai");
    $ds_khuyenmai = $stmt_km->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi khi tải dữ liệu dropdown: " . $e->getMessage();
}

// --- Xử lý POST (khi người dùng nhấn nút "Lưu") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy dữ liệu từ form
    $ma_khach_hang = $_POST['ma_khach_hang'];
    $ma_tour = $_POST['ma_tour'];
    $trang_thai_don_hang = $_POST['trang_thai_don_hang'];
    
    // Xử lý Khuyến mãi (có thể là null)
    $ma_khuyen_mai = !empty($_POST['ma_khuyen_mai']) ? $_POST['ma_khuyen_mai'] : null;
    
    // Các trường khác (ngay_dat sẽ tự động)
    
    try {
        // $pdo đã có sẵn từ index.php
        $sql = "INSERT INTO `dondattour` 
                    (`ma_khach_hang`, `ma_tour`, `ma_khuyen_mai`, `trang_thai_don_hang`) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $ma_khach_hang, 
            $ma_tour,
            $ma_khuyen_mai,
            $trang_thai_don_hang
        ]);

        // Thêm thành công, chuyển hướng về trang danh sách
        header("Location: index.php?module=dondattour&action=list");
        exit(); 

    } catch (PDOException $e) {
        $error_message = "Lỗi khi thêm: " . $e->getMessage();
    }
}
?>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error" style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <form action="index.php?module=dondattour&action=add" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ma_khach_hang">Khách Hàng:</label><br>
            <select id="ma_khach_hang" name="ma_khach_hang" required>
                <option value="">-- Chọn Khách Hàng --</option>
                <?php foreach ($ds_khachhang as $kh): ?>
                    <option value="<?php echo htmlspecialchars($kh['ma_khach_hang']); ?>">
                        <?php echo htmlspecialchars($kh['ho_ten']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="ma_tour">Tour Du Lịch:</label><br>
            <select id="ma_tour" name="ma_tour" required>
                <option value="">-- Chọn Tour --</option>
                <?php foreach ($ds_tour as $tour): ?>
                    <option value="<?php echo htmlspecialchars($tour['maTour']); ?>">
                        <?php echo htmlspecialchars($tour['TenTour']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="ma_khuyen_mai">Khuyến mãi (Nếu có):</label><br>
            <select id="ma_khuyen_mai" name="ma_khuyen_mai">
                <option value="">-- Không áp dụng --</option>
                <?php foreach ($ds_khuyenmai as $km): ?>
                    <option value="<?php echo htmlspecialchars($km['ma_khuyen_mai']); ?>">
                        <?php echo htmlspecialchars($km['gia_tri']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="trang_thai_don_hang">Trạng Thái Đơn Hàng:</label><br>
            <select id="trang_thai_don_hang" name="trang_thai_don_hang" required>
                <option value="Mới">Mới</option>
                <option value="Đã xác nhận">Đã xác nhận</option>
                <option value="Đã hủy">Đã hủy</option>
            </select>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Đơn Đặt Tour</button>
            <a href="index.php?module=dondattour&action=list">Hủy</a>
        </div>
    </form>
</div>