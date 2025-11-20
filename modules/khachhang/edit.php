<?php
// modules/khachhang/edit.php

$page_title = 'Cập nhật Khách Hàng';
$error_message = null;
$kh = null; 

if (isset($_GET['id'])) {
    $ma_khach_hang = $_GET['id'];
} else {
    header("Location: index.php?module=khachhang&action=list");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'];
    $dia_chi = $_POST['dia_chi'];
    
    if (empty($ho_ten)) {
        $error_message = "Họ tên là bắt buộc.";
    } else {
        try {
            $sql = "UPDATE `khachhang` SET
                        `ho_ten` = ?, 
                        `so_dien_thoai` = ?, 
                        `email` = ?,
                        `dia_chi` = ?
                    WHERE `ma_khach_hang` = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $ho_ten, 
                $so_dien_thoai, 
                $email,
                $dia_chi,
                $ma_khach_hang
            ]);

            header("Location: index.php?module=khachhang&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}

if (!isset($error_message)) {
    try {
        $sql_get = "SELECT * FROM khachhang WHERE ma_khach_hang = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_khach_hang]);
        $kh = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$kh) {
            $error_message = "Không tìm thấy khách hàng với ID này.";
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

    <?php if ($kh): ?>
    
    <form action="index.php?module=khachhang&action=edit&id=<?php echo $kh['ma_khach_hang']; ?>" method="POST" style="line-height: 2;">
        
        <div>
            <label for="ho_ten">Họ Tên:</label><br>
            <input type="text" id="ho_ten" name="ho_ten" required style="width: 400px;" 
                   value="<?php echo htmlspecialchars($kh['ho_ten']); ?>">
        </div>
        
        <div>
            <label for="so_dien_thoai">Số Điện Thoại:</label><br>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($kh['so_dien_thoai']); ?>">
        </div>
        
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($kh['email']); ?>">
        </div>

        <div>
            <label for="dia_chi">Địa Chỉ:</label><br>
            <input type="text" id="dia_chi" name="dia_chi" style="width: 400px;" 
                   value="<?php echo htmlspecialchars($kh['dia_chi']); ?>">
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Cập Nhật</button>
            <a href="index.php?module=khachhang&action=list">Hủy</a>
        </div>
    </form>
    
    <?php endif; ?>
</div>