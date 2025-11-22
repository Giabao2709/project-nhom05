<?php
// modules/thanhtoan/delete.php

if (isset($_GET['id'])) {
    $ma_thanh_toan = $_GET['id'];
    
    try {
        $sql = "DELETE FROM thanhtoan WHERE ma_thanh_toan = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_thanh_toan]);

        header("Location: index.php?module=thanhtoan&action=list");
        exit();

    } catch (PDOException $e) {
        echo "Lỗi khi xóa: " . $e->getMessage();
        echo "<br><a href='index.php?module=thanhtoan&action=list'>Quay lại danh sách</a>";
    }
} else {
    header("Location: index.php?module=thanhtoan&action=list");
    exit();
}
?>