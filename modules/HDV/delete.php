<?php
// modules/hdv/delete.php

// 1. Kiểm tra xem id có tồn tại không
if (isset($_GET['id'])) {
    $ma_hdv = $_GET['id'];
    
    try {
        // Lưu ý: Nếu HDV này đã được gán cho 1 tour (trong bảng tourdl),
        // bạn có thể sẽ không xóa được do lỗi khóa ngoại.
        
        $sql = "DELETE FROM hdv WHERE ma_hdv = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_hdv]);

        header("Location: index.php?module=hdv&action=list");
        exit();

    } catch (PDOException $e) {
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><strong>Gợi ý:</strong> Bạn không thể xóa HDV này nếu họ đang được gán cho một Tour.";
        echo "<br><a href='index.php?module=hdv&action=list'>Quay lại danh sách</a>";
    }
} else {
    header("Location: index.php?module=hdv&action=list");
    exit();
}
?>