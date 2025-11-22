<?php
/**
 * MODULE: QUẢN LÝ ĐIỂM ĐẾN (Destinations) - Edit
 * Chức năng: Cập nhật thông tin điểm đến.
 * Dev phụ trách: SV2 - Thành
 */

$page_title = 'Cập nhật Điểm Đến';
$error_message = null;
$diemden = null; 

// 1. Lấy ID của điểm đến cần sửa từ URL
if (isset($_GET['id'])) {
    $ma_diem_den = $_GET['id'];
} else {
    header("Location: index.php?module=diemden&action=list");
    exit();
}

// --- Xử lý POST (Khi người dùng nhấn nút "Lưu Cập Nhật") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy dữ liệu từ form
    $ten_dia_danh = $_POST['ten_dia_danh'];
    $dia_chi = $_POST['dia_chi'];
    $mo_ta_chi_tiet = $_POST['mo_ta_chi_tiet'];
    
    if (empty($ten_dia_danh)) {
        $error_message = "Tên địa danh là bắt buộc.";
    } else {
        try {
            $sql = "UPDATE `diemden` SET
                        `ten_dia_danh` = ?, 
                        `dia_chi` = ?, 
                        `mo_ta_chi_tiet` = ?
                    WHERE `ma_diem_den` = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ten_dia_danh, 
                $dia_chi, 
                $mo_ta_chi_tiet,
                $ma_diem_den
            ]);

            // Cập nhật thành công
            header("Location: index.php?module=diemden&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}

// --- Lấy thông tin CŨ của điểm đến ---
if (!isset($error_message)) {
    try {
        $sql_get = "SELECT * FROM diemden WHERE ma_diem_den = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_diem_den]);
        $diemden = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$diemden) {
            $error_message = "Không tìm thấy điểm đến với ID này.";
        }

    } catch (PDOException $e) {
        $error_message = "Lỗi khi tải thông tin: " . $e->getMessage();
    }
}
?>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error" style="color: red; background: #fee; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <?php if ($diemden): ?>
    
    <form action="index.php?module=diemden&action=edit&id=<?php echo $diemden['ma_diem_den']; ?>" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ten_dia_danh">Tên Địa Danh:</label><br>
            <input type="text" id="ten_dia_danh" name="ten_dia_danh" required style="width: 400px;" 
                   value="<?php echo htmlspecialchars($diemden['ten_dia_danh']); ?>">
        </div>
        
        <div>
            <label for="dia_chi">Địa Chỉ:</label><br>
            <input type="text" id="dia_chi" name="dia_chi" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($diemden['dia_chi']); ?>">
        </div>
        
        <div>
            <label for="mo_ta_chi_tiet">Mô tả chi tiết:</label><br>
            <textarea id="mo_ta_chi_tiet" name="mo_ta_chi_tiet" rows="5" style="width: 400px;"><?php echo htmlspecialchars($diemden['mo_ta_chi_tiet']); ?></textarea>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Cập Nhật</button>
            <a href="index.php?module=diemden&action=list">Hủy</a>
        </div>
    </form>
    
    <?php endif; ?> </div>