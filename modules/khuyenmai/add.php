<?php
// modules/khuyenmai/add.php

$page_title = 'Thêm Khuyến mãi Mới';
$error_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $gia_tri = $_POST['gia_tri'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];
    
    if (empty($gia_tri)) {
        $error_message = "Giá trị (mô tả) là bắt buộc.";
    } else {
        try {
            $sql = "INSERT INTO `khuyenmai` (`gia_tri`, `ngay_bat_dau`, `ngay_ket_thuc`) 
                    VALUES (?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $gia_tri, 
                $ngay_bat_dau, 
                $ngay_ket_thuc
            ]);

            header("Location: index.php?module=khuyenmai&action=list");
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
    
    <form action="index.php?module=khuyenmai&action=add" method="POST" style="line-height: 2;">
        
        <div>
            <label for="gia_tri">Giá trị (ví dụ: Giảm 10%, Giảm 200k):</label><br>
            <input type="text" id="gia_tri" name="gia_tri" required style="width: 400px;">
        </div>
        
        <div>
            <label for="ngay_bat_dau">Ngày Bắt Đầu:</label><br>
            <input type="date" id="ngay_bat_dau" name="ngay_bat_dau">
        </div>

        <div>
            <label for="ngay_ket_thuc">Ngày Kết Thúc:</label><br>
            <input type="date" id="ngay_ket_thuc" name="ngay_ket_thuc">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Khuyến mãi</button>
            <a href="index.php?module=khuyenmai&action=list">Hủy</a>
        </div>
    </form>
</div>