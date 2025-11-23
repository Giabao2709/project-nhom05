<?php
// modules/hdv/add.php

$page_title = 'Thêm Hướng Dẫn Viên Mới';
$error_message = null;

// --- Xử lý POST (khi người dùng nhấn nút "Lưu") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy dữ liệu từ form
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'];
    $chuyen_mon = $_POST['chuyen_mon'];
    $ngon_ngu_thong_thao = $_POST['ngon_ngu_thong_thao'];
    
    // Kiểm tra dữ liệu
    if (empty($ho_ten)) {
        $error_message = "Họ tên là bắt buộc.";
    } else {
        try {
            // $pdo đã có sẵn từ index.php
            // ma_hdv là AUTO_INCREMENT nên không cần thêm
            $sql = "INSERT INTO `hdv` (`ho_ten`, `so_dien_thoai`, `email`, `chuyen_mon`, `ngon_ngu_thong_thao`) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ho_ten, 
                $so_dien_thoai, 
                $email,
                $chuyen_mon,
                $ngon_ngu_thong_thao
            ]);

            // Thêm thành công, chuyển hướng về trang danh sách
            header("Location: index.php?module=hdv&action=list");
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
    
    <form action="index.php?module=hdv&action=add" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ho_ten">Họ Tên:</label><br>
            <input type="text" id="ho_ten" name="ho_ten" required style="width: 400px;">
        </div>
        
        <div>
            <label for="so_dien_thoai">Số Điện Thoại:</label><br>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" style="width: 400px;">
        </div>
        
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" style="width: 400px;">
        </div>

        <div>
            <label for="chuyen_mon">Chuyên Môn:</label><br>
            <input type="text" id="chuyen_mon" name="chuyen_mon" style="width: 400px;">
        </div>

        <div>
            <label for="ngon_ngu_thong_thao">Ngôn Ngữ Thông Thạo:</label><br>
            <input type="text" id="ngon_ngu_thong_thao" name="ngon_ngu_thong_thao" style="width: 400px;">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Hướng Dẫn Viên</button>
            <a href="index.php?module=hdv&action=list">Hủy</a>
        </div>
    </form>
</div>