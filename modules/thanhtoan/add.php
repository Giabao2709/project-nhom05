<?php
// modules/thanhtoan/add.php

$page_title = 'Thêm Thanh Toán Mới';
$error_message = null;
$ds_dondat = [];

// --- Lấy dữ liệu cho Dropdown (Danh sách Đơn đặt tour) ---
try {
    $sql_dd = "SELECT dondattour.ma_booking, khachhang.ho_ten 
               FROM dondattour 
               LEFT JOIN khachhang ON dondattour.ma_khach_hang = khachhang.ma_khach_hang
               ORDER BY dondattour.ma_booking DESC";
    $stmt_dd = $pdo->query($sql_dd);
    $ds_dondat = $stmt_dd->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi khi tải dữ liệu dropdown: " . $e->getMessage();
}

// --- Xử lý POST ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ma_booking = $_POST['ma_booking'];
    $so_tien = $_POST['so_tien'];
    $ngay_thanh_toan = $_POST['ngay_thanh_toan'];
    $phuong_thuc_thanh_toan = $_POST['phuong_thuc_thanh_toan'];
    $trang_thai = $_POST['trang_thai'];
    
    if (empty($ma_booking) || empty($so_tien)) {
        $error_message = "Mã booking và Số tiền là bắt buộc.";
    } else {
        try {
            $sql = "INSERT INTO `thanhtoan` (`ma_booking`, `so_tien`, `ngay_thanh_toan`, `phuong_thuc_thanh_toan`, `trang_thai`) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ma_booking, 
                $so_tien, 
                $ngay_thanh_toan,
                $phuong_thuc_thanh_toan,
                $trang_thai
            ]);

            header("Location: index.php?module=thanhtoan&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi thêm: " . $e->getMessage();
        }
    }
}
?>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error" style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <form action="index.php?module=thanhtoan&action=add" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ma_booking">Đơn Đặt Tour (Booking):</label><br>
            <select id="ma_booking" name="ma_booking" required>
                <option value="">-- Chọn Đơn Đặt Tour --</option>
                <?php foreach ($ds_dondat as $dd): ?>
                    <option value="<?php echo htmlspecialchars($dd['ma_booking']); ?>">
                        Mã: <?php echo htmlspecialchars($dd['ma_booking']); ?> (KH: <?php echo htmlspecialchars($dd['ho_ten']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="so_tien">Số Tiền:</label><br>
            <input type="number" id="so_tien" name="so_tien" required>
        </div>

        <div>
            <label for="ngay_thanh_toan">Ngày Thanh Toán:</label><br>
            <input type="datetime-local" id="ngay_thanh_toan" name="ngay_thanh_toan">
        </div>

        <div>
            <label for="phuong_thuc_thanh_toan">Phương Thức Thanh Toán:</label><br>
            <select id="phuong_thuc_thanh_toan" name="phuong_thuc_thanh_toan">
                <option value="Tiền mặt">Tiền mặt</option>
                <option value="Chuyển khoản">Chuyển khoản</option>
                <option value="Thẻ tín dụng">Thẻ tín dụng</option>
            </select>
        </div>

        <div>
            <label for="trang_thai">Trạng Thái Thanh Toán:</label><br>
            <select id="trang_thai" name="trang_thai">
                <option value="Chưa thanh toán">Chưa thanh toán</option>
                <option value="Đã thanh toán">Đã thanh toán</option>
                <option value="Hoàn tiền">Hoàn tiền</option>
            </select>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Thanh Toán</button>
            <a href="index.php?module=thanhtoan&action=list">Hủy</a>
        </div>
    </form>
</div>