<?php
// modules/khachhang/add.php

$page_title = 'Thêm Khách Hàng Mới';
$error_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'];
    $dia_chi = $_POST['dia_chi'];
    
    if (empty($ho_ten)) {
        $error_message = "Họ tên là bắt buộc.";
    } else {
        try {
            $sql = "INSERT INTO `khachhang` (`ho_ten`, `so_dien_thoai`, `email`, `dia_chi`) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ho_ten, 
                $so_dien_thoai, 
                $email,
                $dia_chi
            ]);

            header("Location: index.php?module=khachhang&action=list");
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
    
    <form action="index.php?module=khachhang&action=add" method="POST" style="line-height: 2;">
        
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
            <label for="dia_chi">Địa Chỉ:</label><br>
            <input type="text" id="dia_chi" name="dia_chi" style="width: 400px;">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Khách Hàng</button>
            <a href="index.php?module=khachhang&action=list">Hủy</a>
        </div>
    </form>
</div>