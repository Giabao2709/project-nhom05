<?php
// modules/diemden/edit.php

$page_title = 'Cập nhật Điểm Đến';
$error_message = null;
$diemden = null; // Biến để lưu thông tin cũ

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
            // Dùng Prepared Statements để chống SQL Injection
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
                $ma_diem_den // ID cho điều kiện WHERE
            ]);

            // Cập nhật thành công, chuyển hướng về trang danh sách
            header("Location: index.php?module=diemden&action=list");
            exit(); 

        } catch (PDOException $e) {
            $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}

// --- Lấy thông tin CŨ của điểm đến (Khi tải trang) ---
if (!isset($error_message)) { // Chỉ chạy nếu chưa có lỗi
    try {
        $sql_get = "SELECT * FROM diemden WHERE ma_diem_den = ?";
        $stmt = $pdo->prepare($sql_get);
        $stmt->execute([$ma_diem_den]);
        $diemden = $stmt->fetch(PDO::FETCH_ASSOC); // Lấy 1 dòng

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
    
    <form action="index.php?module=diemden&action=edit&id=<?php echo $diemden['ma_diem_den']; ?>" method="POST" style="line-height: 2