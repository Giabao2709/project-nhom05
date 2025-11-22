<?php
// modules/hdv/edit.php

$page_title = 'Cập nhật Hướng Dẫn Viên';
$error_message = null;
$hdv = null; // Biến để lưu thông tin cũ

// 1. Lấy ID của HDV cần sửa từ URL
if (isset($_GET['id'])) {
    $ma_hdv = $_GET['id'];
} else {
    header("Location: index.php?module=hdv&action=list");
    exit();
}

// --- Xử lý POST (Khi người dùng nhấn nút "Lưu Cập Nhật") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy dữ liệu từ form
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'];
    $chuyen_mon = $_POST['chuyen_mon'];
    $ngon_ngu_thong_thao = $_POST['ngon_ngu_thong_thao'];
    
    if (empty($ho_ten)) {
        $error_message = "Họ tên là bắt buộc.";
    } else {
        try {
            $sql = "UPDATE `hdv` SET
                        `ho_ten` = ?, 
                        `so_dien_thoai` = ?, 
                        `email` = ?,
                        `chuyen_mon` = ?,
                        `ngon_ngu_thong_thao` = ?
                    WHERE `ma_hdv` = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ho_ten, 
                $so_dien_thoai, 
                $email,
                $chuyen_mon,
                $ngon_ngu_thong_thao,
                $ma_hdv // ID cho điều kiện WHERE
            ]);

            header("Location: index.php?module=hdv&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}

// --- Lấy thông tin CŨ của HDV (Khi tải trang) ---
if (!isset($error_message)) {
    try {
        $sql_get = "SELECT * FROM hdv WHERE ma_hdv = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_hdv]);
        $hdv = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$hdv) {
            $error_message = "Không tìm thấy HDV với ID này.";
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

    <?php if ($hdv): ?>
    
    <form action="index.php?module=hdv&action=edit&id=<?php echo $hdv['ma_hdv']; ?>" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ho_ten">Họ Tên:</label><br>
            <input type="text" id="ho_ten" name="ho_ten" required style="width: 400px;" 
                   value="<?php echo htmlspecialchars($hdv['ho_ten']); ?>">
        </div>
        
        <div>
            <label for="so_dien_thoai">Số Điện Thoại:</label><br>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($hdv['so_dien_thoai']); ?>">
        </div>
        
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($hdv['email']); ?>">
        </div>

        <div>
            <label for="chuyen_mon">Chuyên Môn:</label><br>
            <input type="text" id="chuyen_mon" name="chuyen_mon" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($hdv['chuyen_mon']); ?>">
        </div>

        <div>
            <label for="ngon_ngu_thong_thao">Ngôn Ngữ Thông Thạo:</label><br>
            <input type="text" id="ngon_ngu_thong_thao" name="ngon_ngu_thong_thao" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($hdv['ngon_ngu_thong_thao']); ?>">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Cập Nhật</button>
            <a href="index.php?module=hdv&action=list">Hủy</a>
        </div>
    </form>
    
    <?php endif; // Đóng if ($hdv) ?>
</div>