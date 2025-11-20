<?php
// modules/thanhtoan/edit.php

$page_title = 'Cập nhật Thanh Toán';
$error_message = null;
$tt = null; // Biến lưu thông tin thanh toán cũ
$ds_dondat = [];

if (isset($_GET['id'])) {
    $ma_thanh_toan = $_GET['id'];
} else {
    header("Location: index.php?module=thanhtoan&action=list");
    exit();
}

// --- Lấy dữ liệu cho Dropdown (Giống add.php) ---
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
            $sql = "UPDATE `thanhtoan` SET
                        `ma_booking` = ?, 
                        `so_tien` = ?, 
                        `ngay_thanh_toan` = ?,
                        `phuong_thuc_thanh_toan` = ?,
                        `trang_thai` = ?
                    WHERE `ma_thanh_toan` = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ma_booking, 
                $so_tien, 
                $ngay_thanh_toan,
                $phuong_thuc_thanh_toan,
                $trang_thai,
                $ma_thanh_toan // ID cho điều kiện WHERE
            ]);

            header("Location: index.php?module=thanhtoan&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}

// --- Lấy thông tin CŨ của thanh toán ---
if (!isset($error_message)) {
    try {
        $sql_get = "SELECT * FROM thanhtoan WHERE ma_thanh_toan = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_thanh_toan]);
        $tt = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$tt) {
            $error_message = "Không tìm thấy thanh toán với ID này.";
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

    <?php if ($tt): ?>
    
    <form action="index.php?module=thanhtoan&action=edit&id=<?php echo $tt['ma_thanh_toan']; ?>" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ma_booking">Đơn Đặt Tour (Booking):</label><br>
            <select id="ma_booking" name="ma_booking" required>
                <option value="">-- Chọn Đơn Đặt Tour --</option>
                <?php foreach ($ds_dondat as $dd): ?>
                    <option value="<?php echo htmlspecialchars($dd['ma_booking']); ?>"
                        <?php if ($dd['ma_booking'] == $tt['ma_booking']) echo 'selected'; ?>>
                        Mã: <?php echo htmlspecialchars($dd['ma_booking']); ?> (KH: <?php echo htmlspecialchars($dd['ho_ten']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="so_tien">Số Tiền:</label><br>
            <input type="number" id="so_tien" name="so_tien" required 
                   value="<?php echo htmlspecialchars($tt['so_tien']); ?>">
        </div>

        <div>
            <label for="ngay_thanh_toan">Ngày Thanh Toán:</label><br>
            <?php $ngay_tt_format = !empty($tt['ngay_thanh_toan']) ? date('Y-m-d\TH:i', strtotime($tt['ngay_thanh_toan'])) : ''; ?>
            <input type="datetime-local" id="ngay_thanh_toan" name="ngay_thanh_toan" 
                   value="<?php echo $ngay_tt_format; ?>">
        </div>

        <div>
            <label for="phuong_thuc_thanh_toan">Phương Thức Thanh Toán:</label><br>
            <select id="phuong_thuc_thanh_toan" name="phuong_thuc_thanh_toan">
                <option value="Tiền mặt" <?php if ($tt['phuong_thuc_thanh_toan'] == 'Tiền mặt') echo 'selected'; ?>>Tiền mặt</option>
                <option value="Chuyển khoản" <?php if ($tt['phuong_thuc_thanh_toan'] == 'Chuyển khoản') echo 'selected'; ?>>Chuyển khoản</option>
                <option value="Thẻ tín dụng" <?php if ($tt['phuong_thuc_thanh_toan'] == 'Thẻ tín dụng') echo 'selected'; ?>>Thẻ tín dụng</option>
            </select>
        </div>

        <div>
            <label for="trang_thai">Trạng Thái Thanh Toán:</label><br>
            <select id="trang_thai" name="trang_thai">
                <option value="Chưa thanh toán" <?php if ($tt['trang_thai'] == 'Chưa thanh toán') echo 'selected'; ?>>Chưa thanh toán</option>
                <option value="Đã thanh toán" <?php if ($tt['trang_thai'] == 'Đã thanh toán') echo 'selected'; ?>>Đã thanh toán</option>
                <option value="Hoàn tiền" <?php if ($tt['trang_thai'] == 'Hoàn tiền') echo 'selected'; ?>>Hoàn tiền</option>
            </select>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Cập Nhật</button>
            <a href="index.php?module=thanhtoan&action=list">Hủy</a>
        </div>
    </form>
    
    <?php endif; ?>
</div>