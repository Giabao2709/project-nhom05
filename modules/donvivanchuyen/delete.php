<?php
// modules/donvivanchuyen/delete.php

if (isset($_GET['id'])) {
    $ma_don_vi = $_GET['id'];
    
    try {
        // Lưu ý: Không thể xóa nếu ĐVVC này đang được gán cho một Tour.
        $sql = "DELETE FROM donvivanchuyen WHERE ma_don_vi = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_don_vi]);

        header("Location: index.php?module=donvivanchuyen&action=list");
        exit();

    } catch (PDOException $e) {
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><strong>Gợi ý:</strong> Bạn không thể xóa ĐV Vận Chuyển này nếu nó đang được gán cho một Tour.";
        echo "<br><a href='index.php?module=donvivanchuyen&action=list'>Quay lại danh sách</a>";
    }
} else {
    header("Location: index.php?module=donvivanchuyen&action=list");
    exit();
}
?>