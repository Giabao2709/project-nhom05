<?php
// modules/dondattour/edit.php

$page_title = 'Cập nhật Đơn Đặt Tour';
$error_message = null;
$dd = null; // Biến để lưu thông tin đơn đặt tour cũ

// 1. Lấy ID của đơn cần sửa từ URL
if (isset($_GET['id'])) {
    $ma_booking = $_GET['id'];
} else {
    header("Location: index.php?module=dondattour&action=list");
    exit();
}

// --- Lấy dữ liệu cho các Dropdown (Giống add.php) ---
try {
    $stmt_kh = $pdo->query("SELECT ma_khach_hang, ho_ten FROM khachhang ORDER BY ho_ten");
    $ds_khachhang = $stmt_kh->fetchAll(PDO::FETCH_ASSOC);

    $stmt_tour = $pdo->query("SELECT maTour, TenTour FROM tourdl ORDER BY TenTour");
    $ds_tour = $stmt_tour->fetchAll(PDO::FETCH_ASSOC);

    $stmt_km = $pdo->query("SELECT ma_khuyen_mai, gia_tri FROM khuyenmai ORDER BY ma_khuyen_mai");
    $ds_khuyenmai = $stmt_km->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi khi tải dữ liệu dropdown: " . $e->getMessage();
}


// --- Xử lý POST (Khi người dùng nhấn nút "Lưu Cập Nhật") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy dữ liệu từ form
    $ma_khach_hang = $_POST['ma_khach_hang'];
    $ma_tour = $_POST['ma_tour'];
    $trang_thai_don_hang = $_POST['trang_thai_don_hang'];
    $ma_khuyen_mai = !empty($_POST['ma_khuyen_mai']) ? $_POST['ma_khuyen_mai'] : null;
    
    try {
        $sql = "UPDATE `dondattour` SET
                    `ma_khach_hang` = ?, 
                    `ma_tour` = ?, 
                    `ma_khuyen_mai` = ?,
                    `trang_thai_don_hang` = ?
                WHERE `ma_booking` = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $ma_khach_hang, 
            $ma_tour,
            $ma_khuyen_mai,
            $trang_thai_don_hang,
            $ma_booking // ID cho điều kiện WHERE
        ]);

        header("Location: index.php?module=dondattour&action=list");
        exit(); 

    } catch (PDOException $e) {
        $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
    }
}

// --- Lấy thông tin CŨ của đơn đặt tour (Khi tải trang) ---
if (!isset($error_message)) {
    try {
        $sql_get = "SELECT * FROM dondattour WHERE ma_booking = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_booking]);
        $dd = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$dd) {
            $error_message = "Không tìm thấy đơn đặt tour với ID này.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi khi tải thông tin: " . $e->getMessage();
    }
}
?>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error" style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($dd): ?>
    
    <form action="index.php?module=dondattour&action=edit&id=<?php echo $dd['ma_booking']; ?>" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ma_khach_hang">Khách Hàng:</label><br>
            <select id="ma_khach_hang" name="ma_khach_hang" required>
                <option value="">-- Chọn Khách Hàng --</option>
                <?php foreach ($ds_khachhang as $kh): ?>
                    <option value="<?php echo htmlspecialchars($kh['ma_khach_hang']); ?>"
                        <?php if ($kh['ma_khach_hang'] == $dd['ma_khach_hang']) echo 'selected'; ?>>
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
                    <option value="<?php echo htmlspecialchars($tour['maTour']); ?>"
                        <?php if ($tour['maTour'] == $dd['ma_tour']) echo 'selected'; ?>>
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
                    <option value="<?php echo htmlspecialchars($km['ma_khuyen_mai']); ?>"
                        <?php if ($km['ma_khuyen_mai'] == $dd['ma_khuyen_mai']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($km['gia_tri']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="trang_thai_don_hang">Trạng Thái Đơn Hàng:</label><br>
            <select id="trang_thai_don_hang" name="trang_thai_don_hang" required>
                <option value="Mới" <?php if ($dd['trang_thai_don_hang'] == 'Mới') echo 'selected'; ?>>Mới</option>
                <option value="Đã xác nhận" <?php if ($dd['trang_thai_don_hang'] == 'Đã xác nhận') echo 'selected'; ?>>Đã xác nhận</option>
                <option value="Đã hủy" <?php if ($dd['trang_thai_don_hang'] == 'Đã hủy') echo 'selected'; ?>>Đã hủy</option>
            </select>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Cập Nhật</button>
            <a href="index.php?module=dondattour&action=list">Hủy</a>
        </div>
    </form>
    
    <?php endif; ?>
</div>