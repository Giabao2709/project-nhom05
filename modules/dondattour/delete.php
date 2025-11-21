<?php
// modules/dondattour/delete.php

if (isset($_GET['id'])) {
    $ma_booking = $_GET['id'];
    
    try {
        // Lưu ý: Nếu đơn đặt tour này đã có thanh toán (trong bảng thanhtoan),
        // bạn phải xóa thanh toán trước.
        
        // Bước 1: Xóa các thanh toán liên quan (nếu có)
        $sql_delete_payments = "DELETE FROM thanhtoan WHERE ma_booking = ?";
        $stmt_payments = $pdo->prepare($sql_delete_payments);
        $stmt_payments->execute([$ma_booking]);
        
        // Bước 2: Xóa đơn đặt tour
        $sql = "DELETE FROM dondattour WHERE ma_booking = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_booking]);

        header("Location: index.php?module=dondattour&action=list");
        exit();

    } catch (PDOException $e) {
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><a href='index.php?module=dondattour&action=list'>Quay lại danh sách</a>";
    }
} else {
    header("Location: index.php?module=dondattour&action=list");
    exit();
}
?>