<?php
// modules/khuyenmai/delete.php

if (isset($_GET['id'])) {
    $ma_khuyen_mai = $_GET['id'];
    
    try {
        // Lưu ý: Không thể xóa nếu Khuyến mãi này đã được gán cho Đơn đặt tour.
        $sql = "DELETE FROM khuyenmai WHERE ma_khuyen_mai = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_khuyen_mai]);

        header("Location: index.php?module=khuyenmai&action=list");
        exit();

    } catch (PDOException $e) {
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><strong>Gợi ý:</strong> Bạn không thể xóa Khuyến mãi này nếu nó đã được sử dụng trong một Đơn đặt tour.";
        echo "<br><a href='index.php?module=khuyenmai&action=list'>Quay lại danh sách</a>";
    }
} else {
    header("Location: index.php?module=khuyenmai&action=list");
    exit();
}
?>