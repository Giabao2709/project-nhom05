<?php
// modules/khuyenmai/edit.php

$page_title = 'Cập nhật Khuyến mãi';
$error_message = null;
$km = null; 

if (isset($_GET['id'])) {
    $ma_khuyen_mai = $_GET['id'];
} else {
    header("Location: index.php?module=khuyenmai&action=list");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $gia_tri = $_POST['gia_tri'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];
    
    if (empty($gia_tri)) {
        $error_message = "Giá trị là bắt buộc.";
    } else {
        try {
            $sql = "UPDATE `khuyenmai` SET
                        `gia_tri` = ?, 
                        `ngay_bat_dau` = ?, 
                        `ngay_ket_thuc` = ?
                    WHERE `ma_khuyen_mai` = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $gia_tri, 
                $ngay_bat_dau, 
                $ngay_ket_thuc,
                $ma_khuyen_mai
            ]);

            header("Location: index.php?module=khuyenmai&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}

if (!isset($error_message)) {
    try {
        $sql_get = "SELECT * FROM khuyenmai WHERE ma_khuyen_mai = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_khuyen_mai]);
        $km = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$km) {
            $error_message = "Không tìm thấy khuyến mãi với ID này.";
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

    <?php if ($km): ?>
    
    <form action="index.php?module=khuyenmai&action=edit&id=<?php echo $km['ma_khuyen_mai']; ?>" method="POST" style="line-height: 2;">
        
        <div>
            <label for="gia_tri">Giá trị (ví dụ: Giảm 10%, Giảm 200k):</label><br>
            <input type="text" id="gia_tri" name="gia_tri" required style="width: 400px;" 
                   value="<?php echo htmlspecialchars($km['gia_tri']); ?>">
        </div>
        
        <div>
            <label for="ngay_bat_dau">Ngày Bắt Đầu:</label><br>
            <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" 
                   value="<?php echo htmlspecialchars($km['ngay_bat_dau']); ?>">
        </div>
        
        <div>
            <label for="ngay_ket_thuc">Ngày Kết Thúc:</label><br>
            <input type="date" id="ngay_ket_thuc" name="ngay_ket_thuc" 
                   value="<?php echo htmlspecialchars($km['ngay_ket_thuc']); ?>">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Cập Nhật</button>
            <a href="index.php?module=khuyenmai&action=list">Hủy</a>
        </div>
    </form>
    
    <?php endif; ?>
</div>