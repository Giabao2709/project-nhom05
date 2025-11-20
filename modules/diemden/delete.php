<?php
// modules/diemden/delete.php

// 1. Kiểm tra xem id có tồn tại không
if (isset($_GET['id'])) {
    $ma_diem_den = $_GET['id'];
    
    // $pdo đã có sẵn từ index.php
    try {
        // Lưu ý: Nếu điểm đến này đã được gán cho 1 tour (trong bảng tour_diemden),
        // bạn có thể sẽ không xóa được do lỗi khóa ngoại.
        // Bạn cần xóa liên kết trong 'tour_diemden' trước.
        
        // 2. Viết lệnh SQL DELETE
        $sql = "DELETE FROM diemden WHERE ma_diem_den = ?";
        $stmt = $pdo->prepare($sql);
        
        // 3. Thực thi
        $stmt->execute([$ma_diem_den]);

        // 4. Chuyển hướng về trang danh sách
        header("Location: index.php?module=diemden&action=list");
        exit();

    } catch (PDOException $e) {
        // Nếu có lỗi (ví dụ: lỗi khóa ngoại)
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><strong>Gợi ý:</strong> Bạn không thể xóa điểm đến này nếu nó đang được sử dụng bởi một Tour.";
        echo "<br><a href='index.php?module=diemden&action=list'>Quay lại danh sách</a>";
    }
} else {
    // Không có id, chuyển về trang list
    header("Location: index.php?module=diemden&action=list");
    exit();
}
?>