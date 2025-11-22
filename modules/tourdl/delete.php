<?php
// modules/tourdl/delete.php

// 1. Kiểm tra xem id có tồn tại không
if (isset($_GET['id'])) {
    $maTour = $_GET['id'];
    
    // $pdo đã có sẵn từ index.php
    try {
        // Chú ý: Cần kiểm tra ràng buộc khóa ngoại
        // Nếu Tour này đã có người đặt (trong dondattour),
        // bạn có thể sẽ không xóa được (tùy cài đặt CSDL)
        // hoặc bạn cần xóa các đơn đặt tour trước.
        
        // 2. Viết lệnh SQL DELETE
        $sql = "DELETE FROM tourdl WHERE maTour = ?";
        $stmt = $pdo->prepare($sql);
        
        // 3. Thực thi
        $stmt->execute([$maTour]);

        // 4. Chuyển hướng về trang danh sách
        header("Location: index.php?module=tourdl&action=list");
        exit();

    } catch (PDOException $e) {
        // Nếu có lỗi (ví dụ: lỗi khóa ngoại)
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><a href='index.php?module=tourdl&action=list'>Quay lại danh sách</a>";
    }
} else {
    // Không có id, chuyển về trang list
    header("Location: index.php?module=tourdl&action=list");
    exit();
}
?>