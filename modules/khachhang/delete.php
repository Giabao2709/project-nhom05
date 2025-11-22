<?php
// modules/khachhang/delete.php

if (isset($_GET['id'])) {
    $ma_khach_hang = $_GET['id'];
    
    try {
        // Lưu ý: Không thể xóa nếu Khách hàng này đã có Đơn đặt tour.
        $sql = "DELETE FROM khachhang WHERE ma_khach_hang = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_khach_hang]);

        header("Location: index.php?module=khachhang&action=list");
        exit();

    } catch (PDOException $e) {
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><strong>Gợi ý:</strong> Bạn không thể xóa Khách hàng này nếu họ đã có Đơn đặt tour.";
        echo "<br><a href='index.php?module=khachhang&action=list'>Quay lại danh sách</a>";
    }
} else {
    header("Location: index.php?module=khachhang&action=list");
    exit();
}
?>