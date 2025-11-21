<?php
// modules/donvivanchuyen/edit.php

$page_title = 'Cập nhật ĐV Vận Chuyển';
$error_message = null;
$dv = null; 

if (isset($_GET['id'])) {
    $ma_don_vi = $_GET['id'];
} else {
    header("Location: index.php?module=donvivanchuyen&action=list");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ten_don_vi = $_POST['ten_don_vi'];
    $loai_phuong_tien = $_POST['loai_phuong_tien'];
    $thong_tin_lien_lac = $_POST['thong_tin_lien_lac'];
    
    if (empty($ten_don_vi)) {
        $error_message = "Tên đơn vị là bắt buộc.";
    } else {
        try {
            $sql = "UPDATE `donvivanchuyen` SET
                        `ten_don_vi` = ?, 
                        `loai_phuong_tien` = ?, 
                        `thong_tin_lien_lac` = ?
                    WHERE `ma_don_vi` = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ten_don_vi, 
                $loai_phuong_tien, 
                $thong_tin_lien_lac,
                $ma_don_vi
            ]);

            header("Location: index.php?module=donvivanchuyen&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}

if (!isset($error_message)) {
    try {
        $sql_get = "SELECT * FROM donvivanchuyen WHERE ma_don_vi = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_don_vi]);
        $dv = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$dv) {
            $error_message = "Không tìm thấy ĐV Vận Chuyển với ID này.";
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

    <?php if ($dv): ?>
    
    <form action="index.php?module=donvivanchuyen&action=edit&id=<?php echo $dv['ma_don_vi']; ?>" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ten_don_vi">Tên Đơn Vị:</label><br>
            <input type="text" id="ten_don_vi" name="ten_don_vi" required style="width: 400px;" 
                   value="<?php echo htmlspecialchars($dv['ten_don_vi']); ?>">
        </div>
        
        <div>
            <label for="loai_phuong_tien">Loại Phương Tiện:</label><br>
            <input type="text" id="loai_phuong_tien" name="loai_phuong_tien" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($dv['loai_phuong_tien']); ?>">
        </div>
        
        <div>
            <label for="thong_tin_lien_lac">Thông Tin Liên Lạc:</label><br>
            <input type="text" id="thong_tin_lien_lac" name="thong_tin_lien_lac" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($dv['thong_tin_lien_lac']); ?>">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Cập Nhật</button>
            <a href="index.php?module=donvivanchuyen&action=list">Hủy</a>
        </div>
    </form>
    
    <?php endif; ?>
</div>