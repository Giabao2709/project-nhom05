<?php
// modules/diemden/add.php

$page_title = 'Thêm Điểm Đến Mới';
$error_message = null;

// --- Xử lý POST (khi người dùng nhấn nút "Lưu") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy dữ liệu từ form
    $ten_dia_danh = $_POST['ten_dia_danh'];
    $dia_chi = $_POST['dia_chi'];
    $mo_ta_chi_tiet = $_POST['mo_ta_chi_tiet'];
    
    // Kiểm tra dữ liệu (bạn có thể thêm các kiểm tra khác)
    if (empty($ten_dia_danh)) {
        $error_message = "Tên địa danh là bắt buộc.";
    } else {
        try {
            // $pdo đã có sẵn từ index.php
            // ma_diem_den là AUTO_INCREMENT nên không cần thêm
            $sql = "INSERT INTO `diemden` (`ten_dia_danh`, `dia_chi`, `mo_ta_chi_tiet`) 
                    VALUES (?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ten_dia_danh, 
                $dia_chi, 
                $mo_ta_chi_tiet
            ]);

            // Thêm thành công, chuyển hướng về trang danh sách
            header("Location: index.php?module=diemden&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi thêm: " . $e->getMessage();
        }
    }
}
?>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error" style="color: red; background: #fee; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <form action="index.php?module=diemden&action=add" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ten_dia_danh">Tên Địa Danh:</label><br>
            <input type="text" id="ten_dia_danh" name="ten_dia_danh" required style="width: 400px;">
        </div>
        
        <div>
            <label for="dia_chi">Địa Chỉ:</label><br>
            <input type="text" id="dia_chi" name="dia_chi" style="width: 400px;">
        </div>
        
        <div>
            <label for="mo_ta_chi_tiet">Mô tả chi tiết:</label><br>
            <textarea id="mo_ta_chi_tiet" name="mo_ta_chi_tiet" rows="5" style="width: 400px;"></textarea>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Điểm Đến</button>
            <a href="index.php?module=diemden&action=list">Hủy</a>
        </div>
    </form>
</div>