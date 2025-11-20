<?php
// modules/donvivanchuyen/add.php

$page_title = 'Thêm ĐV Vận Chuyển Mới';
$error_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ten_don_vi = $_POST['ten_don_vi'];
    $loai_phuong_tien = $_POST['loai_phuong_tien'];
    $thong_tin_lien_lac = $_POST['thong_tin_lien_lac'];
    
    if (empty($ten_don_vi)) {
        $error_message = "Tên đơn vị là bắt buộc.";
    } else {
        try {
            $sql = "INSERT INTO `donvivanchuyen` (`ten_don_vi`, `loai_phuong_tien`, `thong_tin_lien_lac`) 
                    VALUES (?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ten_don_vi, 
                $loai_phuong_tien, 
                $thong_tin_lien_lac
            ]);

            header("Location: index.php?module=donvivanchuyen&action=list");
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
    
    <form action="index.php?module=donvivanchuyen&action=add" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ten_don_vi">Tên Đơn Vị:</label><br>
            <input type="text" id="ten_don_vi" name="ten_don_vi" required style="width: 400px;">
        </div>
        
        <div>
            <label for="loai_phuong_tien">Loại Phương Tiện (ví dụ: Xe 45 chỗ):</label><br>
            <input type="text" id="loai_phuong_tien" name="loai_phuong_tien" style="width: 400px;">
        </div>
        
        <div>
            <label for="thong_tin_lien_lac">Thông Tin Liên Lạc (SĐT, Email...):</label><br>
            <input type="text" id="thong_tin_lien_lac" name="thong_tin_lien_lac" style="width: 400px;">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Đơn Vị</button>
            <a href="index.php?module=donvivanchuyen&action=list">Hủy</a>
        </div>
    </form>
</div>